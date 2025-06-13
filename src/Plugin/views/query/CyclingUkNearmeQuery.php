<?php

namespace Drupal\cyclinguk_nearme\Plugin\views\query;

use Drupal\Core\Render\Markup;
use Drupal\views\Annotation\ViewsQuery;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Drupal\views\Plugin\views\query\QueryPluginBase;
use Drupal\views\ResultRow;
use Drupal\views\ViewExecutable;

/**
 * Geographical database query plugin.
 *
 * @ViewsQuery(
 *   id = "cyclinguk_nearme",
 *   title = @Translation("Geographical data"),
 *   help = @Translation("Query against the geographical database API.")
 * )
 */
class CyclingUkNearmeQuery extends QueryPluginBase {


  /**
   * List of content types to request.
   *
   * @var string[]
   */
  protected array $contentTypes = [
    'routes',
    'pois',
    'events',
    'groups',
    'posts',
    'areas',
  ];

  /**
   * List of sort files, array of ['field' => $field, 'dir' => $dir].
   *
   * @var array
   */
  protected array $sortBy = ['field' => 'node_title', 'dir' => 'ASC'];

  /**
   * Point (in WGS84 coordinates) to find things within radius (in miles) of.
   *
   * @var array[string]
   */
  protected array $latLongRadius = ['lat' => 54.5, 'lon' => -2.5, 'miles' => 1000];

  protected array $tags = [];

  /**
   * The type of API method we're calling.
   *
   * @var string
   */
  protected string $methodType = '';

  /**
   * The UUID of the area to show, for 'area_id' method.
   *
   * @var string
   */
  protected string $areaId = '';

  /**
   * The name of the area to show, for 'area_name' method.
   *
   * @var string
   */
  protected string $areaName = '';

  /**
   * The UUID of the route to show, for 'route_id' method.
   *
   * @var string
   */
  protected string $routeId = '';

  /**
   * The name of the route to show, for 'route_name' method.
   *
   * @var string
   */
  protected string $routeName = '';

  /**
   * Required member variable for use by QueryPluginBase::setWhereGroup();
   *
   * @var array
   */
  protected array $where = [];

  /**
   * Required member variable for use by QueryPluginBase::setWhereGroup();
   *
   * @var array
   */
  protected array $having = [];

  /**
   * Override SQL method to do nothing.
   *
   * @noinspection PhpUnusedParameterInspection
   */
  public function ensureTable($table, $relationship = NULL): string {
    return '';
  }

  /**
   * Override SQL method to do nothing.
   *
   * @noinspection PhpUnusedParameterInspection
   */
  public function addField($table, $field, $alias = '', $params = []) {
    return $field;
  }

  /**
   * @param string|array $tags Comma-separated list of tags, or array of tags.
   *
   * @return void
   */
  public function addTags($tags) {
    if (is_array($tags)) {
      $this->tags = array_unique(array_merge($this->tags, $tags));
    }
    else {
      // ToDo array merge?
      $this->tags = array_unique(array_merge($this->tags, preg_split('/, ?/', $tags)));
    }
  }

  /**
   * This is the main function that calls the remote API.
   *
   * Values to set: $view->result, $view->total_rows, $view->execute_time,
   * $view->current_page, $view->pager->total_items.
   * {@inheritdoc}
   */
  public function execute(ViewExecutable $view): void {
    // If we're using a map display, put the relevant data into the single
    // "result" of the query. We're not using ResultRow objects here.
    if ($view->getStyle()->getPluginId() === 'cyclinguk_nearme_map') {
      switch ($this->methodType) {
        case 'latlonrad':
          $view->result[] = [
            'type' => 'latlonrad',
            'data' => $this->latLongRadius,
          ];
          break;

        case 'area_name':
          $view->result[] = [
            'type' => 'area_name',
            'data' => $this->areaName,
          ];
          break;

        case 'route_id':
          $view->result[] = [
            'type' => 'route_id',
            'data' => $this->routeId,
          ];
          break;

        default:
          $view->result[] = [
            'type' => 'none',
          ];
          break;
      }
      return;
    }
    // If we get here, we're not using a map display, so use the API to get
    // results.
    // Construct the remote API request URL.
    $config = \Drupal::config('cyclinguk_nearme.settings');
    if ($config->get('cyclinguk_nearme.api_get_mode') == 'live') {
      $request_url = $config->get('cyclinguk_nearme.api_get_url_live');
    }
    else {
      $request_url = $config->get('cyclinguk_nearme.api_get_url_test');
    }
    $query_arguments = [];
    switch ($this->methodType) {
      case 'latlonrad':
        $query_arguments[] = 'lat=' . $this->latLongRadius['lat'];
        $query_arguments[] = 'lon=' . $this->latLongRadius['lon'];
        $query_arguments[] = 'radius_km=' . ($this->latLongRadius['miles'] * 8 / 5);
        break;

      case 'area_id':
        $query_arguments[] = 'area=' . $this->areaId;
        break;

      case 'area_name':
        $query_arguments[] = 'area_name=' . $this->areaName;
        break;

      case 'route_id':
        // @todo variable radius.
        $query_arguments[] = 'route=' . $this->routeId;
        $query_arguments[] = 'radius=0.2';
        break;

      case  'route_name':
        // @todo variable radius.
        $query_arguments[] = 'route_name=' . $this->routeName;
        $query_arguments[] = 'radius=0.2';
        break;

    }
    if ($this->tags) {
      $encoded_tags = array_map('rawurlencode', $this->tags);
      $query_arguments[] = 'tags=' . implode(',', $encoded_tags);
    }

    // Initialize the pager and let it modify the "query" to add limits (offset and limit).
    $view->initPager();
    $view->pager->query();

    // Add content type filter, if needed.
    if ($this->contentTypes) {
      $query_arguments[] = 'content=' . implode(',', $this->contentTypes);
    }
    if (count($query_arguments)) {
      $request_url .= '?' . implode('&', $query_arguments);
    }
    // To debug API requests, uncomment:
    // $this->messenger()->addMessage('API Request: ' . $request_url);
    // Request the remote API results with an httpClient.
    $response_code = 0;
    $client = \Drupal::httpClient();
    $response = NULL;
    try {
      if ($config->get('cyclinguk_nearme.debug_messages')) {
        $this->messenger()
          ->addStatus(MarkUp::create('DEBUG: API Request URL: <pre style="font-size: 85%; line-height: 1.2;">' . $request_url . '</pre>'));
      }
      $response = $client->request('get', $request_url);
      $response_code = $response->getStatusCode();
    } catch (RequestException $e) {
      if ($e->hasResponse() && $e->getResponse()) {
        $message = Markup::create($this->t('Remote Server Error: %code <br>Query: %query <br>%error.', [
          '%code' => $e->getCode(),
          '%query' => $request_url,
          '%error' => $e->getResponse()->getBody(),
        ]));
      }
      else {
        $message = $e->getMessage();
      }
      $this->messenger()->addError($message);
      watchdog_exception('cyclinguk_nearme', $e);
    } catch (GuzzleException $e) {
      watchdog_exception('cyclinguk_nearme', $e);
      return;
    }
    if ($response && $response_code == 200) {
      try {
        $data = json_decode($response->getBody()->getContents(), FALSE, 512, JSON_THROW_ON_ERROR);
      } catch (\JsonException $e) {
        watchdog_exception('cyclinguk_nearme', $e);
        return;
      }
      if ($config->get('cyclinguk_nearme.debug_messages')) {
        $this->messenger()
          ->addStatus(MarkUp::create('DEBUG: API Results: <pre style="font-size: 85%; line-height: 1.2;">' . print_r($data, TRUE) . '</pre>'));
      }
      foreach ($data->results as $type => $result) {
        foreach ($result as $uuid) {
          $row['UUID'] = $uuid;
          $row['type'] = substr($type, 0, -1);
          $view->result[] = new ResultRow($row);
        }
      }
      try {
        $this->loadEntities($view->result);
      } catch (\Exception $e) {
        watchdog_exception('cyclinguk_nearme', $e);
        return;
      }
      // $this->messenger->addMessage(print_r($this->sortBy, TRUE));
      $sort = $this->sortBy;
      switch ($this->sortBy['field']) {
        case 'UUID':
          usort($view->result, static function ($a, $b) {
            return $a->UUID <=> $b->UUID;
          });
          break;

        case 'node_title':
          usort($view->result, static function ($a, $b) {
            if ($a->nid == NULL && $b->nid != NULL) {
              return 1;
            }
            if ($a->nid != NULL && $b->nid == NULL) {
              return -1;
            }
            return $a->node_title <=> $b->node_title;
          });
          break;

        default:
          break;
      }
      if ($sort['dir'] === 'DESC') {
        $view->result = array_reverse($view->result);
      }
      foreach ($view->result as $index => $result) {
        // 'index' key is required.
        $result->index = $index;
      }
      if (isset($view->pager)) {
        // Tell view and pager how many results we have in total across all pages.
        $view->total_rows = count($view->result);
        $view->pager->total_items = $view->total_rows;
        // Extract just this page's results.
        if ($this->limit > 0) {
          $view->result = array_slice($view->result, $this->offset, $this->limit);
        }
      }
    }
    if (isset($view->pager)) {// Trigger pager postExecute() and updatePageInfo to create the pager object.
      $view->pager->postExecute($view->result);
      $view->pager->updatePageInfo();
    }
  }

  /**
   *
   */
  public function getItemTypes() {
    return $this->contentTypes;
  }

  public function getTagsArray() {
    return $this->tags;
  }

  /**
   * Record a filter criterium.
   *
   * @noinspection PhpUnusedParameterInspection
   */
  public function addWhere($group, $field, $value = NULL, $operator = NULL): void {
    $field = ltrim($field, '.');
    if ($field === 'type') {
      $this->contentTypes = $value;
    }
    elseif ($field === 'pointradius') {
      $this->methodType = 'latlonrad';
      $this->latLongRadius = $value;
    }
    elseif ($field === 'area_id') {
      $this->methodType = 'area_id';
      $this->areaId = $value;
    }
    elseif ($field === 'area_name') {
      $this->methodType = 'area_name';
      $this->areaName = $value;
    }
    elseif ($field === 'route_id') {
      $this->methodType = 'route_id';
      $this->routeId = $value;
    }
    elseif ($field === 'route_name') {
      $this->methodType = 'route_name';
      $this->routeName = $value;
    }
  }

  /**
   * Record a sort criterium.
   *
   * @noinspection PhpUnusedParameterInspection
   */
  public function addOrderBy($table, $field, $direction): void {
    $this->sortBy = ['field' => $field, 'dir' => $direction];
  }

  /**
   * Load nodes.
   *
   * @param array $results
   *   Array of results data.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   *
   * @noinspection PhpParameterByRefIsNotUsedAsReferenceInspection
   */
  public function loadEntities(&$results): void {
    /** @var \Drupal\node\NodeStorage $entity_storage */
    $entity_storage = \Drupal::entityTypeManager()
      ->getStorage('node');
    foreach ($results as $row) {
      $nid = NULL;
      $nids = $entity_storage->getQuery()
        ->condition('status', 1)
        ->condition('uuid', $row->UUID)
        ->accessCheck(FALSE)
        ->execute();
      $nid = reset($nids);
      if ($nid) {
        $row->nid = $nid;
        $entities = $entity_storage->loadMultiple([$nid]);
        $entity = reset($entities);
        $row->node_title = $entity->label();
        $row->_entity = $entity;
      }
      else {
        $row->nid = 0;
        $row->_entity = NULL;
        $row->node_title = '';
      }
    }
  }

}
