<?php

class UseyourDrive_Cache {

  /**
   *  @var UseyourDrive
   */
  public $processor;
  public $_updated = false;
  protected $_last_cache_id = '';
  protected $_last_update = '';
  protected $_checked_for_updates = false;
  protected $_location = null;
  protected $_cache_handle = null;

  /* How often do we need to poll for changes? (half hour) */
  protected $_max_change_age = 1800;

  /**
   * The Cache as Tree Class
   *  @var UseyourDrive_Tree
   */
  protected $_cache;

  public function __construct(UseyourDrive $processor) {
    $this->processor = $processor;

    $this->loadCache();

    /* Remove any results that hasn't found a parent */
    $this->_cache->removeInvalidNodes();

    add_action('shutdown', array($this, 'updateCache'));
  }

  public function setLocation($location) {
    switch ($location) {
      case 'database':
        $this->_location = 'database';
        break;
      case 'filesystem':
        $this->_location = 'filesystem';
        break;
    }
  }

  public function loadCache() {
    $this->_location = $this->processor->settings['cache'];

    if ($this->_location === 'filesystem') {
      $cache = $this->_readLocalCache('close');
    }

    //Fall Back to database cache if cache file isn't writable
    if ($this->_location === 'database' || $cache === false) {
      $cache = get_option('use_your_drive_cache', array('last_update' => null, 'last_cache_id' => '', 'cache' => ''));
    }

    if ($cache['cache'] !== '') {
      $this->_cache = unserialize($cache['cache']);
      if ($this->_cache === false || $this->_cache === '') {
        $this->_cache = new UseyourDrive_Tree;
      }
    } else {
      $this->_cache = new UseyourDrive_Tree;
    }

    $this->_last_cache_id = $cache['last_cache_id'];
    $this->_last_update = $cache['last_update'];
  }

  public function getDatabaseLocked() {
    return get_option('use_your_drive_cache_locked', false);
  }

  public function setDatabaseLocked($lock = true) {
    $value = ($lock) ? time() : false;
    return update_option('use_your_drive_cache_locked', $value);
  }

  protected function _readLocalCache($close = false) {

    if (empty($this->_cache_handle)) {
      $this->createLocalLock(LOCK_SH);
    }

    clearstatcache();
    rewind($this->_cache_handle);

    $data = fread($this->_cache_handle, filesize(USEYOURDRIVE_CACHEDIR . '/index'));

    if ($close !== false) {
      $this->unlockLocalCache();
    }


    $cache = json_decode($data, true);

    if ($cache === null) {
      $cache = array('last_update' => null, 'last_cache_id' => '', 'cache' => '');
    }


    return $cache;
  }

  protected function _saveLocalCache() {
    if (!$this->createLocalLock(LOCK_EX)) {
      return false;
    }

    $json_options = 0;
    if (defined('JSON_PRETTY_PRINT')) {
      $json_options |= JSON_PRETTY_PRINT;  // Supported in PHP 5.4+
    }

    $data = array(
      'last_update' => $this->_last_update,
      'last_cache_id' => $this->_last_cache_id,
      'cache' => serialize($this->_cache)
    );

    $encodeddata = json_encode($data, $json_options);

    ftruncate($this->_cache_handle, 0);
    rewind($this->_cache_handle);

    $result = fwrite($this->_cache_handle, $encodeddata);

    $this->unlockLocalCache();
    $this->_updated = false;
    return true;
  }

  protected function _saveDatabaseCache() {
    $this->waitForLock();

    @update_option('use_your_drive_cache', array(
              'last_update' => $this->_last_update,
              'last_cache_id' => $this->_last_cache_id,
              'cache' => serialize($this->_cache)));

    $this->unlockCache();
    $this->_updated = false;
    return true;
  }

  public function createLocalLock($type) {
    /*  Check if file exists */
    if (!file_exists(USEYOURDRIVE_CACHEDIR . '/index')) {
      @file_put_contents(USEYOURDRIVE_CACHEDIR . '/index', json_encode(array('last_update' => null, 'last_cache_id' => '', 'cache' => '')));

      if (!is_writable(USEYOURDRIVE_CACHEDIR . '/index')) {
        die('Cache file / directory not writable');
      }
    }

    /* Check if the file is more than 1 minute old. */
    $requires_unlock = ((filemtime(USEYOURDRIVE_CACHEDIR . '/index') + 60) < (time()));

    /* Check if file is already opened and locked in this process */
    if (empty($this->_cache_handle)) {
      $this->_cache_handle = fopen(USEYOURDRIVE_CACHEDIR . '/index', 'c+');
    }

    set_time_limit(60);
    if (!flock($this->_cache_handle, $type | LOCK_NB)) {
      // if the file cannot be unlocked and the last time it was modified was 1 minute, assume that the previous process died and unlock the file manually
      if ($requires_unlock) {
        $this->unlockLocalCache();
      }
      //Try to lock the file again
      flock($this->_cache_handle, LOCK_EX);
    }
    set_time_limit(60);

    return true;
  }

  public function unlockLocalCache() {
    if (!empty($this->_cache_handle)) {
      flock($this->_cache_handle, LOCK_UN);
      fclose($this->_cache_handle);
      $this->_cache_handle = null;
    }

    clearstatcache();
    return true;
  }

  public function unlockCache() {
    $this->setDatabaseLocked(false);
  }

  private function waitForLock() {
    $locked = $this->getDatabaseLocked();
    if (!$locked) {
      //DB cache isn't locked, set new lock
      $this->setDatabaseLocked();
      return false;
    } elseif (((int) $this->getDatabaseLocked() + 5) < time()) {
      // DB Cache is locked, but longer than 5 seconds. Assume the owning process died off and set new lock
      $this->setDatabaseLocked();
      return false;
    }

    // Else wait max 5 seconde
    // 5 x 1000 = 5 seconds
    $tries = 5;
    $cnt = 0;

    do {
      // 1000 ms is a long time to sleep, but it does stop the server from burning all resources on polling locks..
      usleep(1000000);
      $this->loadCache();
      $cnt++;
    } while ($cnt <= $tries && $this->getDatabaseLocked());

    $this->setDatabaseLocked();
    return false;
  }

  public function setLastCacheId($id) {
    $this->_last_cache_id = $id;
    return $this->_last_cache_id;
  }

  public function getLastCacheId() {
    return $this->_last_cache_id;
  }

  public function setLastUpdate() {
    $this->_last_update = time();
    $this->_updated = true;
    return $this->_last_update;
  }

  public function getLastUpdate() {
    return $this->_last_update;
  }

  public function setRoot($entry) {
    $this->_cache->setRoot($entry);
    $this->setLastUpdate();
    $this->updateCache();
    return $this->_cache->getRoot();
  }

  public function getRoot() {
    $root = $this->_cache->getRoot();
    if ($root->getId() === '***Root***') {
      return false;
    }
    if ($root->hasItem() === false) {
      return false;
    }
    return $root;
  }

  public function removeFromCache($entry) {
    if (is_a($entry, 'Google_Service_Drive_DriveFile')) {
      $id = $entry->getId();
    } else {
      $id = $entry;
    }

    try {
      $node = $this->_cache->searchNodeId($id);
      $this->_cache->removeNode($node);
      $this->setLastUpdate();
    } catch (Exception $ex) {
      return false;
    }
    return true;
  }

  public function addToCache($entry) {
    $newentry = $this->_cache->searchNodeId($entry->getId());
    if ($newentry === false || !$newentry->hasItem()) {
      $this->setLastUpdate();

      $newentry = $this->_cache->createNode($entry->getId());
      $newentry->setItem($entry);
    }

    /* Set Check if entry isn't a folder */
    if ($entry->getMimeType() !== 'application/vnd.google-apps.folder') {
      $newentry->setChecked(true);
    } else {
      $newentry->setChecked(false);
    }

    /* If entry hasn't any parents, add it to root */
    if (!$newentry->hasParents()) {
      $this->getRoot()->addChild($newentry);
      return $newentry;
    }

    /* Parent doesn't exists yet in our cache
     * We need to get this parents */
    $getparents = array();
    foreach ($entry->getParents() as $parent) {
      $parent_in_tree = $this->isCached($parent->getId(), false, false);
      if ($parent_in_tree === false) {
        $getparents[] = $parent->getId();
      }
    }

    if (count($getparents) > 0) {
      $parents = $this->processor->getMultipleEntries($getparents);
      foreach ($parents as $parent) {
        $this->addToCache($parent);
      }
    }

    /* Add entry to all parents */
    foreach ($entry->getParents() as $parent) {
      $parent_in_tree = $this->_cache->searchNodeId($parent->getId());
      /* Parent does already exists in our cache */
      if ($parent_in_tree !== false) {
        $newentry->setParent($parent_in_tree);
      }
    }

    return $newentry;
  }

  public function getEntryById($id, $parent = null) {
    $entry_in_tree = $this->_cache->searchNodeId($id, $parent);
    return $entry_in_tree;
  }

  public function getEntryByName($name, $parent = null) {
    $entry_in_tree = $this->_cache->searchNodeTitle($name, $parent);
    return $entry_in_tree;
  }

  public function isCached($id, $title = false, $hardrefresh = false) {

    if ($title !== false) {
      $entry_in_tree = $this->_cache->searchNodeTitle($title);
    } else {
      $entry_in_tree = $this->_cache->searchNodeId($id);
    }


    if ($entry_in_tree !== false) {

      if ($hardrefresh) {
        $this->_cache->removeNode($entry_in_tree);
        return false;
      }

      if ($entry_in_tree->isExpired() && !$entry_in_tree->getRoot()) {
        $entry_in_tree->setItem(null);
        return false;
      }

      /* Check if the children of the cached item are alread cached */
      if ($entry_in_tree->getChecked()) {
        return $entry_in_tree;
      }
    }

    return false;
  }

  public function refreshCache() {
    /* Check if we need to check for updates */
    $currenttime = time();
    if (($this->_last_update + $this->_max_change_age) > $currenttime) {
      return;
    }

    $params = array(
      "userIp" => $this->processor->userip,
      "maxResults" => 500,
      "includeDeleted" => true,
      "includeSubscribed" => true);

    if ($this->getLastCacheId() === '') {
      $params['maxResults'] = 1;
    } else {
      $params['startChangeId'] = (string) ((int) $this->getLastCacheId() + 1);
    }

    $changes = $this->processor->getChanges($params);

    if ($changes !== false) {
      if ($this->getLastCacheId() === '') {
        $this->setLastCacheId($changes->getLargestChangeId());
        $this->setLastUpdate();
      } else {
        $this->processChanges($changes);

        $pageToken = $changes->getNextPageToken();
        if ($pageToken) {
          $this->refreshCache();
        }

        if (count($changes->getItems()) === 0) {
          $this->setLastCacheId($changes->getLargestChangeId());
          $this->setLastUpdate();
        }
      }
    }
  }

  public function resetCache() {
    $this->_cache = new UseyourDrive_Tree;
    $this->setLastCacheId('');
    $this->setLastUpdate();
    $this->updateCache();
  }

  public function processChanges(Google_Service_Drive_ChangeList $changes) {

    /* @var $change Google_Service_Drive_Change */
    foreach ($changes->getItems() as $change) {
      $entryId = $change->getFileId();

      $entry_in_tree = $this->_cache->searchNodeId($entryId);

      if ($change->getDeleted() === true) {
        /* Delete file from cache if is deleted */
        if (($entry_in_tree !== false)) {
          $this->_cache->removeNode($entry_in_tree);
        }
      } else if ($entry_in_tree !== false) {
        /* Update File info */
        $entry_in_tree->setItem(null);
        $this->addToCache($change->getFile());
      } else {
        /* Check if parent is known */
        foreach ($change->getFile()->getParents() as $parent) {
          $parent_in_tree = $this->_cache->searchNodeId($entryId);

          if ($parent_in_tree !== false) {
            /* Add new file to Cache */
            $this->addToCache($change->getFile());
            break;
          }
        }
      }

      $this->setLastCacheId($change->getId());
      $this->setLastUpdate();
    }
  }

  public function updateCache() {
    if ($this->_updated === true) {

      switch ($this->_location) {
        case 'filesystem':
          $saved = $this->_saveLocalCache();
          break;
        case 'database':
          $saved = $this->_saveDatabaseCache();
          break;
      }
    }
  }

  public function __destruct() {
    $this->updateCache();
  }

}

class UseyourDrive_Node {

  public $id = null;
  public $parents = array();
  public $children = array();
  public $item = null;
  public $root = false;
  public $parentfound = false;
  public $checked = false;
  public $expires;

  /* Max age of a entry: needed for download/thumbnails urls (1 hour?) */
  protected $_max_entry_age = 3600; //

  function __construct($params = null) {
    foreach ($params as $key => $val)
      $this->$key = $val;
    if ($this->hasParents()) {
      foreach ($this->getParents() as $parent) {
        $parent->addChild($this);
      }
    }

    $this->expires = time() + $this->_max_entry_age;
  }

  public function addChild(UseyourDrive_Node $node) {
    $this->children[$node->getId()] = $node;
    return $this;
  }

  public function removeChild(UseyourDrive_Node $node) {
    unset($this->children[$node->getId()]);
    return $this;
  }

  public function removeChilds() {
    foreach ($this->getChildren() as $child) {
      $this->removeChild($child);
    }
    return $this;
  }

  public function hasItem() {
    return ($this->getItem() !== null);
  }

  /* @return Google_Service_Drive_DriveFile */

  public function getItem() {
    return $this->item;
  }

  public function setItem($entry) {
    $this->item = $entry;
    if ($entry !== null) {
      $this->setExpired();
    }
    return $this;
  }

  public function getPath($toParentId) {
    if ($toParentId === $this->getId()) {
      return '/' . $this->getItem()->getTitle();
    }

    if ($this->hasParents()) {
      foreach ($this->getParents() as $parent) {
        $path = $parent->getPath($toParentId);
        if ($path !== false) {
          return $path . '/' . $this->getItem()->getTitle();
        }
      }
    }

    return false;
  }

  public function getExpired() {
    return $this->expires;
  }

  public function setExpired() {
    $this->expires = time() + $this->_max_entry_age;
    return $this;
  }

  public function isExpired() {
    /* Check if the entry needs to be refreshed */
    if ($this->expires < time()) {
      return true;
    }

    return false;
  }

  public function hasParents() {
    return (count($this->parents) > 0);
  }

  public function getParents() {
    return $this->parents;
  }

  public function setParent(UseyourDrive_Node $pnode) {

    if ($this->getParentFound() === false) {
      $this->removeParents();
      $this->parentfound = true;
    }

    $this->parents[$pnode->getId()] = $pnode;
    $this->parents[$pnode->getId()]->addChild($this);

    return $this;
  }

  public function removeParents() {
    foreach ($this->getParents() as $parent) {
      $this->removeParent($parent);
    }
    return $this;
  }

  public function removeParent(UseyourDrive_Node $pnode) {
    if ($this->hasParents() && isset($this->parents[$pnode->getId()])) {
      $this->parents[$pnode->getId()]->removeChild($this);
      unset($this->parents[$pnode->getId()]);
    }
    return $this;
  }

  public function hasChildren() {
    return (count($this->children) > 0);
  }

  public function getChildren() {
    return $this->children;
  }

  public function isInFolder($in_folder) {

    /* Is node just the folder? */
    if ($this->getId() === $in_folder) {
      return true;
    }

    /* Has the node Parents? */
    if ($this->hasParents() === false) {
      return false;
    }

    foreach ($this->getParents() as $parent) {
      /* First check if one of the parents is the root folder */
      if ($parent->isInFolder($in_folder) === true) {
        return true;
      }
    }

    return false;
  }

  public function getId() {
    return $this->id;
  }

  public function setRoot($v) {
    $this->root = $v;
    return $this;
  }

  public function getRoot() {
    return $this->root;
  }

  public function setParentFound($v) {
    $this->parentfound = $v;
    return $this;
  }

  public function getParentFound() {
    return $this->parentfound;
  }

  public function setChecked($v) {
    $this->checked = $v;
    return $this;
  }

  public function getChecked() {
    return $this->checked;
  }

}

class UseyourDrive_Tree {

  public $root = null;

  function __construct() {
    $this->root = new UseyourDrive_Node(array('id' => '***Root***'));
  }

  public function setRoot($entry) {
    $this->root = new UseyourDrive_Node(array('id' => $entry->getId(), 'parentfound' => true));
    $this->root->setItem($entry);
    $this->root->setRoot(true);
    return $this;
  }

  public function getRoot() {
    return $this->root;
  }

  public function createNode($id, $pnode = false) {
    if ($pnode === false) {
      $pnode = $this->root;
    }
    $child = new UseyourDrive_Node(array(
      'parents' => array($pnode->getId() => $pnode),
      'parentfound' => false,
      'id' => $id));

    return $child;
  }

  /*
   * @return UseyourDrive_Node
   */

  public function searchNodeId($search_id, UseyourDrive_Node $in_node = null) {
    if ($in_node === null) {
      $in_node = $this->root;
    }

    /* Is it the node itself? */
    if ($in_node->getId() === $search_id) {
      return $in_node;
    }

    /* Is Id in Children */
    if ($in_node->hasChildren()) {
      /* First search all Children for id */
      foreach ($in_node->getChildren() as $child) {
        if ($child->getId() === $search_id) {
          return $child;
        }
      }

      /* Search in Childrens Children */
      foreach ($in_node->getChildren() as $child) {
        $result = $this->searchNodeId($search_id, $child);

        if ($result !== false) {
          return $result;
        }
      }
    }

    /* Nothing found */
    return false;
  }

  /*
   * @return UseyourDrive_Node
   */

  public function searchNodeTitle($search_title, UseyourDrive_Node $in_node = null) {
    if ($in_node === null) {
      $in_node = $this->root;
    }


    /* Is it the node itself? */
    if (($in_node->getItem() !== null) && ($in_node->getItem()->getTitle() === $search_title)) {
      return $in_node;
    }

    /* Is Id in Children */
    if ($in_node->hasChildren()) {
      /* First search all Children for id */
      foreach ($in_node->getChildren() as $child) {
        if (($child->getItem() !== null) && ($child->getItem()->getTitle() === $search_title)) {
          return $child;
        }
      }

      /* Search in Childrens Children */
      foreach ($in_node->getChildren() as $child) {
        $result = $this->searchNodeTitle($search_title, $child);

        if ($result !== false) {
          return $result;
        }
      }
    }

    /* Nothing found */
    return false;
  }

  public function removeNode($node) {

    if (!is_a($node, 'UseyourDrive_Node')) {
      return;
    }

    if ($node->getRoot() === true) {
      $node->removeChilds();
      $node->setItem(null);
      $node->setChecked(false);
    } else {
      $node->removeParents();
      unset($node);
    }
  }

  public function removeInvalidNodes() {
    /* Remove nodes without a parent */
    foreach ($this->getRoot()->getChildren() as $child) {
      if ($child->getParentFound() === false) { {
          $this->removeNode($child);
        }
      }
    }
  }

}
