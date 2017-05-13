<?php

require_once 'UseyourDrive_Cache.php';

class UseyourDrive_Processor {

  public $options = array();
  protected $lists = array();
  protected $listtoken = '';
  protected $_rootFolder = null;
  protected $_lastFolder = null;
  protected $_folderPath = null;
  protected $_requestedEntry = null;
  protected $_userFolder = false;
  protected $_loadscripts = array('general' => false, 'files' => false, 'upload' => false, 'mediaplayer' => false, 'qtip' => false);
  public $userip;

  /**
   * The Cache as Tree Class
   *  @var UseyourDrive_Cache
   */
  public $cache = null;
  public $mobile = false;

  /**
   * Construct the plugin object
   */
  public function __construct() {
    $this->settings = get_option('use_your_drive_settings');
    $this->lists = get_option('use_your_drive_lists', array());
    $this->cache = new UseyourDrive_Cache($this);
    $this->userip = $_SERVER['REMOTE_ADDR'];

    if (isset($_REQUEST['mobile']) && ($_REQUEST['mobile'] === 'true')) {
      $this->mobile = true;
    }
  }

  public function startProcess() {
    if (isset($_REQUEST['action'])) {

      $authorized = $this->_IsAuthorized();

      if (($authorized === true) && ($_REQUEST['action'] === 'useyourdrive-revoke')) {
        $this->revokeToken();
        die();
      }

      if ((!isset($_REQUEST['listtoken']))) {
        die();
      }

      $this->listtoken = $_REQUEST['listtoken'];
      if (!isset($this->lists[$this->listtoken])) {
        die();
      }

      $this->options = $this->lists[$this->listtoken];


      if (is_wp_error($authorized)) {
        if ($this->options['debug'] === '1') {
          die($authorized->get_error_message());
        } else {
          die();
        }
      }

      /* Refresh Cache if needed */
      //$this->cache->resetCache();
      $this->cache->refreshCache();

      /* Set rootFolder */
      if ($this->options['user_upload_folders'] === 'manual') {
        $userfolder = get_user_option('use_your_drive_linkedto');
        if (is_array($userfolder) && isset($userfolder['folderid'])) {
          $this->_rootFolder = $userfolder['folderid'];
        } else {
          $defaultuserfolder = get_site_option('use_your_drive_guestlinkedto');
          if (is_array($defaultuserfolder) && isset($defaultuserfolder['folderid'])) {
            $this->_rootFolder = $defaultuserfolder['folderid'];
          } else {
            die();
          }
        }
      } else if (($this->options['user_upload_folders'] === 'auto') && !$this->checkUserRole($this->options['view_user_folders_role'])) {
        $this->_rootFolder = $this->createUserFolder();
      } else {
        $this->_rootFolder = $this->options['root'];
      }

      if (!$this->checkUserRole($this->options['view_role'])) {
        die();
      }

      $this->_lastFolder = $this->_rootFolder;
      if (!empty($_REQUEST['lastFolder'])) {
        $this->_lastFolder = $_REQUEST['lastFolder'];
      }

      $this->_requestedEntry = $this->_lastFolder;
      if (!empty($_REQUEST['id'])) {
        $this->_requestedEntry = $_REQUEST['id'];
      }

      if (!empty($_REQUEST['folderPath'])) {
        $this->_folderPath = unserialize(base64_decode($_REQUEST['folderPath']));

        if ($this->_folderPath === false || $this->_folderPath === null || !is_array($this->_folderPath)) {
          $this->_folderPath = array($this->_rootFolder);
        }

        $key = array_search($this->_requestedEntry, $this->_folderPath);
        if ($key !== false) {
          array_splice($this->_folderPath, $key);
          if (count($this->_folderPath) === 0) {
            $this->_folderPath = array($this->_rootFolder);
          }
        }
      } else {
        $this->_folderPath = array($this->_rootFolder);
      }

      $this->_gZipCompression();

      switch ($_REQUEST['action']) {

        case 'useyourdrive-get-filelist':
          if (is_wp_error($authorized)) {
// No valid token is set
            echo json_encode(array(
              'rawpath' => '',
              'folderPath' => '',
              'lastFolder' => '',
              'breadcrumb' => '',
              'html' => ''));

            die();
          }

          if (isset($_REQUEST['query']) && $this->options['search'] === '1') { // Search files
            $filelist = $this->searchFiles();
          } else {
            $filelist = $this->getFilesList(); // Read folder
          }

          die();
          break;

        case 'useyourdrive-download':
        case 'useyourdrive-preview':
        case 'useyourdrive-create-zip':
        case 'useyourdrive-create-link':
        case 'useyourdrive-embedded':
          if (is_wp_error($authorized)) {
            die();
          }

          if ($_REQUEST['action'] === 'useyourdrive-preview') {
            $file = $this->downloadFile();
          } else {
            if (!$this->checkUserRole($this->options['download_role'])) {
              die();
            }
            if ($_REQUEST['action'] === 'useyourdrive-download') {
              $file = $this->downloadFile();
            } elseif ($_REQUEST['action'] === 'useyourdrive-create-zip') {
              $file = $this->createZip();
            } elseif ($_REQUEST['action'] === 'useyourdrive-embedded') {
              $links = $this->createLinks(false);
              echo json_encode($links);
            } else {
              if (isset($_REQUEST['entries'])) {
                $links = $this->createLinks();
                echo json_encode($links);
              } else {
                $link = $this->createLink();
                echo json_encode($link);
              }

              die();
            }
          }

          break;
        case 'useyourdrive-get-gallery':
          if (is_wp_error($authorized)) {
// No valid token is set
            echo json_encode(array('lastpath' => base64_encode(serialize($this->_lastFolder)), 'folder' => '', 'html' => ''));
            die();
          }

          if (isset($_REQUEST['query']) && $this->options['search'] === '1') { // Search files
            $imagelist = $this->searchImageFiles();
          } else {
            $imagelist = $this->getImagesList(); // Read folder
          }
          die();
          break;

        case 'useyourdrive-upload-file':
          $user_can_upload = false;
          if ($this->options['upload'] === '1') {
            if ($this->checkUserRole($this->options['upload_role'])) {
              $user_can_upload = true;
            }
          }

          if (is_wp_error($authorized) || $user_can_upload === false) {
            die();
          }

          switch ($_REQUEST['type']) {
            case 'do-upload':
              $upload = $this->uploadFile();
              break;
            case 'get-status':
              $status = $this->getUploadStatus();
              break;
          }

          die();
          break;

        case 'useyourdrive-delete-entry':
        case 'useyourdrive-delete-entries':
//Check if user is allowed to delete entry
          $user_can_delete = false;
          if ($this->options['delete'] === '1') {
            if ($this->checkUserRole($this->options['delete_files_role']) || $this->checkUserRole($this->options['delete_folders_role'])) {
              $user_can_delete = true;
            }
          }

          if (is_wp_error($authorized) || $user_can_delete === false) {
            echo json_encode(array('result' => '-1', 'msg' => __('Failed to delete entry', 'useyourdrive')));
            die();
          }
          if ($_REQUEST['action'] === 'useyourdrive-delete-entries') {
            $entries = $this->deleteEntries();

            foreach ($entries as $entry) {
              if (is_wp_error($entry)) {
                echo json_encode(array('result' => '-1', 'msg' => __('Not all entries could be deleted', 'useyourdrive')));
                die();
              }
            }
            echo json_encode(array('result' => '1', 'msg' => __('Entry was deleted', 'useyourdrive')));
          } else {
            $file = $this->deleteEntry();
            if (is_wp_error($file)) {
              echo json_encode(array('result' => '-1', 'msg' => $file->get_error_message()));
            } else {
              echo json_encode(array('result' => '1', 'msg' => __('Entry was deleted', 'useyourdrive')));
            }
          }


          die();
          break;

        case 'useyourdrive-rename-entry':
//Check if user is allowed to rename entry
          $user_can_rename = false;
          if ($this->options['rename'] === '1') {
            if ($this->checkUserRole($this->options['rename_files_role']) || $this->checkUserRole($this->options['rename_folders_role'])) {
              $user_can_rename = true;
            }
          }

          if (is_wp_error($authorized) || $user_can_rename === false) {
            echo json_encode(array('result' => '-1', 'msg' => __('Failed to rename entry', 'useyourdrive')));
            die();
          }

//Strip unsafe characters
          $newname = urldecode($_REQUEST['newname']);
          $special_chars = array("?", "/", "\\", "<", ">", ":", "\"", "*");
          $newname = str_replace($special_chars, '', $newname);

          $file = $this->renameEntry($newname);

          if (is_wp_error($file)) {
            echo json_encode(array('result' => '-1', 'msg' => $file->get_error_message()));
          } else {
            echo json_encode(array('result' => '1', 'msg' => __('Entry was renamed', 'useyourdrive')));
          }

          die();
          break;


        case 'useyourdrive-move-entry':
          /* Check if user is allowed to move entry */
          $user_can_moveentry = false;
          if ($this->options['move'] === '1') {
            if ($this->checkUserRole($this->options['move_files_role']) || $this->checkUserRole($this->options['move_folders_role'])) {
              $user_can_moveentry = true;
            }
          }

          if (is_wp_error($authorized) || $user_can_moveentry === false) {
            echo json_encode(array('result' => '-1', 'msg' => __('Failed to move', 'useyourdrive')));
            die();
          }

          $file = $this->moveEntry($_REQUEST['target']);

          if (is_wp_error($file)) {
            echo json_encode(array('result' => '-1', 'msg' => $file->get_error_message()));
          } else {
            echo json_encode(array('result' => '1', 'msg' => __('Entry was moved', 'useyourdrive')));
          }

          die();
          break;

        case 'useyourdrive-edit-description-entry':
          //Check if user is allowed to rename entry
          $user_can_editdescription = false;
          if ($this->options['editdescription'] === '1') {
            if ($this->checkUserRole($this->options['editdescription_role'])) {
              $user_can_editdescription = true;
            }
          }

          if (is_wp_error($authorized) || $user_can_editdescription === false) {
            echo json_encode(array('result' => '-1', 'msg' => __('Failed to edit description', 'useyourdrive')));
            die();
          }

          $newdescription = urldecode($_REQUEST['newdescription']);
          $result = $this->descriptionEntry($newdescription);

          if (is_wp_error($result)) {
            echo json_encode(array('result' => '-1', 'msg' => $result->get_error_message()));
          } else {
            echo json_encode(array('result' => '1', 'msg' => __('Description was edited', 'useyourdrive'), 'description' => $result));
          }

          die();
          break;


        case 'useyourdrive-add-folder':

//Check if user is allowed to add folder
          $user_can_addfolder = false;
          if ($this->options['addfolder'] === '1') {
            if ($this->checkUserRole($this->options['addfolder_role'])) {
              $user_can_addfolder = true;
            }
          }

          if (is_wp_error($authorized) || $user_can_addfolder === false) {
            echo json_encode(array('result' => '-1', 'msg' => __('Failed to add folder', 'useyourdrive')));
            die();
          }

//Strip unsafe characters
          $newfolder = urldecode($_REQUEST['newfolder']);
          $special_chars = array("?", "/", "\\", "<", ">", ":", "\"", "*");
          $newfolder = str_replace($special_chars, '', $newfolder);

          $file = $this->addFolder($newfolder);

          if (is_wp_error($file)) {
            echo json_encode(array('result' => '-1', 'msg' => $file->get_error_message()));
          } else {
            echo json_encode(array('result' => '1', 'msg' => __('Folder', 'useyourdrive') . ' ' . $newfolder . ' ' . __('was added', 'useyourdrive'), 'lastfolder' => $file->getId()));
          }
          die();
          break;

        case 'useyourdrive-get-playlist':
          if (is_wp_error($authorized)) {
            die();
          }

          $playlist = $this->getMediaList();

          break;

        default:
          die('Use-your-Drive: ' . __('no valid AJAX call', 'useyourdrive'));
      }
    } else {
      die();
    }

    die();
  }

  public function createFromShortcode($atts) {

    $atts = (is_string($atts)) ? array() : $atts;
    $atts = $this->removeDeprecatedOptions($atts);

//Create a unique identifier
    $this->listtoken = md5(USEYOURDRIVE_VERSION . serialize($atts));

//Read shortcode
    extract(shortcode_atts(array(
      'dir' => false,
      'startid' => false,
      'mode' => 'files',
      'userfolders' => '0',
      'usertemplatedir' => '',
      'viewuserfoldersrole' => 'administrator',
      'showfiles' => '1',
      'showfolders' => '1',
      'filesize' => '1',
      'filedate' => '1',
      'filelayout' => 'grid',
      'showcolumnnames' => '1',
      'showext' => '1',
      'sortfield' => 'name',
      'sortorder' => 'asc',
      'showbreadcrumb' => '1',
      'candownloadzip' => '0',
      'showsharelink' => '0',
      'showrefreshbutton' => '1',
      'roottext' => __('Start', 'useyourdrive'),
      'search' => '1',
      'searchcontents' => '0',
      'searchfrom' => 'parent',
      'include' => '*',
      'includeext' => '*',
      'exclude' => '*',
      'excludeext' => '*',
      'maxwidth' => '100%',
      'maxheight' => '',
      'viewrole' => 'administrator|editor|author|contributor|subscriber|pending|guest',
      'downloadrole' => 'administrator|editor|author|contributor|subscriber|pending|guest',
      'previewinline' => '1',
      'forcedownload' => '0',
      'maximages' => '25',
      'quality' => '90',
      'slideshow' => '0',
      'pausetime' => '5000',
      'targetheight' => '150',
      'mediaextensions' => '',
      'autoplay' => '0',
      'hideplaylist' => '0',
      'covers' => '0',
      'linktomedia' => '0',
      'linktoshop' => '',
      'notificationupload' => '0',
      'notificationdownload' => '0',
      'notificationdeletion' => '0',
      'notificationemail' => '%admin_email%',
      'upload' => '0',
      'uploadext' => '.',
      'uploadrole' => 'administrator|editor|author|contributor|subscriber',
      'maxfilesize' => '0',
      'convert' => '0',
      'delete' => '0',
      'deletefilesrole' => 'administrator|editor',
      'deletefoldersrole' => 'administrator|editor',
      'deletetotrash' => '0',
      'rename' => '0',
      'renamefilesrole' => 'administrator|editor',
      'renamefoldersrole' => 'administrator|editor',
      'move' => '0',
      'movefilesrole' => 'administrator|editor',
      'movefoldersrole' => 'administrator|editor',
      'editdescription' => '0',
      'editdescriptionrole' => 'administrator|editor',
      'addfolder' => '0',
      'addfolderrole' => 'administrator|editor',
      'mcepopup' => '0',
      'debug' => '0',
      'demo' => '0'
                    ), $atts));

    if (!isset($this->lists[$this->listtoken])) {

      $authorized = $this->_isAuthorized();

      if (is_wp_error($authorized)) {
        if ($debug === '1') {
          return "<div id='message' class='error'><p>" . $authorized->get_error_message() . "</p></div>";
        }
        return "";
      }

      $this->lists[$this->listtoken] = array();

//Set Session Data
      switch ($mode) {
        case 'audio':
        case 'video':
          $mediaextensions = explode('|', $mediaextensions);
          break;
        case 'gallery':
          $includeext = ($includeext == '*') ? 'gif|jpg|jpeg|png|bmp' : $includeext;
          $uploadext = ($uploadext == '.') ? 'gif|jpg|jpeg|png|bmp' : $uploadext;
          $mediaextensions = '';
        default:
          $mediaextensions = '';
          break;
      }

      $rootfolder = $this->getFolder(true);
      if (is_wp_error($rootfolder)) {
        if ($debug === '1') {
          return "<div id='message' class='error'><p>" . $rootfolder->get_error_message() . "</p></div>";
        }
      } elseif ($rootfolder === false) {
        if ($debug === '1') {
          return "<div id='message' class='error'><p>" . __('Please authorize Use-your-Drive', 'useyourdrive') . "</p></div>";
        }
      }
      $rootfolderid = $rootfolder->getItem()->getId();

      if ($dir === false) {
        $dir = $rootfolderid;
      }

//Force $candownloadzip = 0 if we can't use ZipArchive
      if (!class_exists('ZipArchive')) {
        $candownloadzip = '0';
      }

// Explode roles
      $viewrole = explode('|', $viewrole);
      $downloadrole = explode('|', $downloadrole);
      $uploadrole = explode('|', $uploadrole);
      $deletefilesrole = explode('|', $deletefilesrole);
      $deletefoldersrole = explode('|', $deletefoldersrole);
      $renamefilesrole = explode('|', $renamefilesrole);
      $renamefoldersrole = explode('|', $renamefoldersrole);
      $movefilesrole = explode('|', $movefilesrole);
      $movefoldersrole = explode('|', $movefoldersrole);
      $editdescriptionrole = explode('|', $editdescriptionrole);
      $addfolderrole = explode('|', $addfolderrole);
      $viewuserfoldersrole = explode('|', $viewuserfoldersrole);

      $this->options = array(
        'root' => $dir,
        'base' => $rootfolderid,
        'startid' => $startid,
        'mode' => $mode,
        'user_upload_folders' => $userfolders,
        'user_template_dir' => $usertemplatedir,
        'view_user_folders_role' => $viewuserfoldersrole,
        'media_extensions' => $mediaextensions,
        'autoplay' => $autoplay,
        'hideplaylist' => $hideplaylist,
        'covers' => $covers,
        'linktomedia' => $linktomedia,
        'linktoshop' => $linktoshop,
        'show_files' => $showfiles,
        'show_folders' => $showfolders,
        'show_filesize' => $filesize,
        'show_filedate' => $filedate,
        'filelayout' => $filelayout,
        'show_columnnames' => $showcolumnnames,
        'show_ext' => $showext,
        'sort_field' => $sortfield,
        'sort_order' => $sortorder,
        'show_breadcrumb' => $showbreadcrumb,
        'can_download_zip' => $candownloadzip,
        'show_sharelink' => $showsharelink,
        'show_refreshbutton' => $showrefreshbutton,
        'root_text' => $roottext,
        'search' => $search,
        'searchcontents' => $searchcontents,
        'searchfrom' => $searchfrom,
        'include' => explode('|', htmlspecialchars_decode($include)),
        'include_ext' => explode('|', strtolower($includeext)),
        'exclude' => explode('|', htmlspecialchars_decode($exclude)),
        'exclude_ext' => explode('|', strtolower($excludeext)),
        'maxwidth' => $maxwidth,
        'maxheight' => $maxheight,
        'view_role' => $viewrole,
        'download_role' => $downloadrole,
        'previewinline' => $previewinline,
        'forcedownload' => $forcedownload,
        'maximages' => $maximages,
        'notificationupload' => $notificationupload,
        'notificationdownload' => $notificationdownload,
        'notificationdeletion' => $notificationdeletion,
        'notificationemail' => $notificationemail,
        'upload' => $upload,
        'upload_ext' => strtolower($uploadext),
        'upload_role' => $uploadrole,
        'maxfilesize' => $maxfilesize,
        'convert' => $convert,
        'delete' => $delete,
        'delete_files_role' => $deletefilesrole,
        'delete_folders_role' => $deletefoldersrole,
        'deletetotrash' => $deletetotrash,
        'rename' => $rename,
        'rename_files_role' => $renamefilesrole,
        'rename_folders_role' => $renamefoldersrole,
        'move' => $move,
        'move_files_role' => $movefilesrole,
        'move_folders_role' => $movefoldersrole,
        'editdescription' => $editdescription,
        'editdescription_role' => $editdescriptionrole,
        'addfolder' => $addfolder,
        'addfolder_role' => $addfolderrole,
        'quality' => $quality,
        'targetheight' => $targetheight,
        'slideshow' => $slideshow,
        'pausetime' => $pausetime,
        'mcepopup' => $mcepopup,
        'debug' => $debug,
        'demo' => $demo,
        'expire' => strtotime('+1 weeks'),
        'listtoken' => $this->listtoken);

      $this->updateLists();

//Create userfolders if needed

      if (($this->options['user_upload_folders'] === 'auto')) {
        if ($this->settings['userfolder_onfirstvisit'] === 'Yes') {

          $allusers = array();
          $roles = array_diff($this->options['view_role'], $this->options['view_user_folders_role']);

          foreach ($roles as $role) {
            $users_query = new WP_User_Query(array(
              'fields' => 'all_with_meta',
              'role' => $role,
              'orderby' => 'display_name'
            ));
            $results = $users_query->get_results();
            if ($results) {
              $allusers = array_merge($allusers, $results);
            }
          }

          $userfolder = $this->createUserFolder($allusers);
        }
      }
    } else {
      $this->options = $this->lists[$this->listtoken];
      $this->updateLists();
    }

    ob_start();
    $this->renderTemplate();

    return ob_get_clean();
  }

  public function renderTemplate() {

// Render the  template
    if ($this->checkUserRole($this->options['view_role'])) {

      $dataid = (($this->options['user_upload_folders'] !== '0') && !$this->checkUserRole($this->options['view_user_folders_role'])) ? '' : $this->options['root'];

      if ($this->options['user_upload_folders'] === 'manual') {
        $userfolder = get_user_option('use_your_drive_linkedto');
        if (is_array($userfolder) && isset($userfolder['folderid'])) {
          $dataid = $userfolder['folderid'];
        } else {
          $defaultuserfolder = get_site_option('use_your_drive_guestlinkedto');
          if (is_array($defaultuserfolder) && isset($defaultuserfolder['folderid'])) {
            $dataid = $defaultuserfolder['folderid'];
          } else {
            include(sprintf("%s/templates/noaccess.php", USEYOURDRIVE_ROOTDIR));
            return;
          }
        }
      }

      $dataid = ($this->options['startid'] !== false) ? $this->options['startid'] : $dataid;

      echo "<div id='UseyourDrive'>";
      echo "<noscript><div class='UseyourDrive-nojsmessage'>" . __('To view the Google Drive folders, you need to have JavaScript enabled in your browser', 'useyourdrive') . ".<br/>";
      echo "<a href='http://www.enable-javascript.com/' target='_blank'>" . __('To do so, please follow these instructions', 'useyourdrive') . "</a>.</div></noscript>";

      switch ($this->options['mode']) {
        case 'files':

          $this->loadScripts('files');

          echo "<div id='UseyourDrive-$this->listtoken' class='UseyourDrive files uyd-" . $this->options['filelayout'] . " jsdisabled' data-list='files' data-token='$this->listtoken' data-id='" . $dataid . "' data-path='" . base64_encode(serialize($this->_folderPath)) . "' data-sort='" . $this->options['sort_field'] . ":" . $this->options['sort_order'] . "' data-org-id='" . $dataid . "' data-org-path='" . base64_encode(serialize($this->_folderPath)) . "' data-layout='" . $this->options['filelayout'] . "'>";


          if ($this->options['mcepopup'] === 'shortcode') {
            echo "<div class='selected-folder'><strong>" . __('Selected folder', 'useyourdrive') . ": </strong><span class='current-folder-raw'></span></div>";
          }

          include(sprintf("%s/templates/frontend.php", USEYOURDRIVE_ROOTDIR));
          $this->renderUploadform();
          echo "</div>";
          break;

        case 'gallery':

          $this->loadScripts('files');

          $nextimages = '';
          if (($this->options['maximages'] !== '0')) {
            $nextimages = "data-loadimages='" . $this->options['maximages'] . "'";
          }

          echo "<div id='UseyourDrive-$this->listtoken' class='UseyourDrive gridgallery jsdisabled' data-list='gallery' data-token='$this->listtoken' data-id='" . $dataid . "' data-path='" . base64_encode(serialize($this->_folderPath)) . "' data-sort='" . $this->options['sort_field'] . ":" . $this->options['sort_order'] . "' data-org-id='" . $dataid . "' data-org-path='" . base64_encode(serialize($this->_folderPath)) . "' data-targetheight='" . $this->options['targetheight'] . "' data-slideshow='" . $this->options['slideshow'] . "' data-pausetime='" . $this->options['pausetime'] . "' $nextimages>";
          include(sprintf("%s/templates/gallery.php", USEYOURDRIVE_ROOTDIR));
          $this->renderUploadform();
          echo "</div>";
          break;

        case 'video':
        case 'audio':
          $skin = $this->settings['mediaplayer_skin'];
          $mp4key = array_search('mp4', $this->options['media_extensions']);
          if ($mp4key !== false) {
            unset($this->options['media_extensions'][$mp4key]);
            if ($this->options['mode'] === 'video') {
              if (!in_array('m4v', $this->options['media_extensions'])) {
                $this->options['media_extensions'][] = 'm4v';
              }
            } else {
              if (!in_array('m4a', $this->options['media_extensions'])) {
                $this->options['media_extensions'][] = 'm4a';
              }
            }
          }

          $oggkey = array_search('ogg', $this->options['media_extensions']);
          if ($oggkey !== false) {
            unset($this->options['media_extensions'][$oggkey]);
            if ($this->options['mode'] === 'video') {
              if (!in_array('ogv', $this->options['media_extensions'])) {
                $this->options['media_extensions'][] = 'ogv';
              }
            } else {
              if (!in_array('oga', $this->options['media_extensions'])) {
                $this->options['media_extensions'][] = 'oga';
              }
            }
          }

          $extensions = join(',', $this->options['media_extensions']);
          $coverclass = 'nocover';
          if ($this->options['mode'] === 'audio' && $this->options['covers'] === '1') {
            $coverclass = 'cover';
          }

          $this->loadScripts('mediaplayer');

          if ($extensions !== '') {
            echo "<div id='UseyourDrive-$this->listtoken' class='UseyourDrive media " . $this->options['mode'] . " $coverclass jsdisabled' data-list='media' data-token='$this->listtoken' data-extensions='" . $extensions . "' data-id='" . $dataid . "' data-sort='" . $this->options['sort_field'] . ":" . $this->options['sort_order'] . "' data-autoplay='" . $this->options['autoplay'] . "'>";
            include(sprintf("%s/skins/%s/player.php", USEYOURDRIVE_ROOTDIR, $skin));
            echo "</div>";
          } else {
            echo '<strong>Use-your-Drive:</strong>' . __('Please update your mediaplayer shortcode', 'useyourdrive');
          }

          break;
      }
      echo "</div>";

      $this->loadScripts('general');
    }
  }

  public function renderUploadform() {
    $user_can_upload = false;
    if ($this->checkUserRole($this->options['upload_role'])) {
      $user_can_upload = true;
    }

    /* Direct upload (remove cancel and start button) */

    if ($this->options['upload'] === '1' && $user_can_upload) {
      $post_max_size_bytes = min(UseyourDrive_return_bytes(ini_get('post_max_size')), UseyourDrive_return_bytes(ini_get('upload_max_filesize')));
      $max_file_size = ($this->options['maxfilesize'] !== '0') ? $this->options['maxfilesize'] : $post_max_size_bytes;
      $post_max_size_str = UseyourDrive_bytesToSize1024($max_file_size);
      $acceptfiletypes = '.(' . $this->options['upload_ext'] . ')$';

      $this->loadScripts('upload');
      include(sprintf("%s/templates/uploadform.php", USEYOURDRIVE_ROOTDIR));
    }
  }

  protected function loadScripts($template) {
    if ($this->_loadscripts[$template] === true) {
      return false;
    }

    switch ($template) {
      case 'general':
        wp_enqueue_script('UseyourDrive');
        break;
      case 'files':
        wp_enqueue_style('qtip');
        wp_enqueue_style('UseyourDrive-dialogs');
        wp_enqueue_script('json2');
        wp_enqueue_script('jquery-ui-mouse');

        if (($this->options['delete'] === '1' && ($this->checkUserRole($this->options['delete_files_role']) && $this->checkUserRole($this->options['delete_folders_role']))) ||
                ($this->options['addfolder'] === '1' && $this->checkUserRole($this->options['addfolder_role'])) ||
                ($this->options['rename'] === '1' && ($this->checkUserRole($this->options['rename_files_role']) || $this->checkUserRole($this->options['rename_folders_role']))) ||
                ($this->options['show_sharelink'] === '1')) {
          wp_enqueue_script('jquery-ui-button');
          wp_enqueue_script('jquery-ui-position');
          wp_enqueue_script('jquery-ui-dialog');
        }

        if ($this->options['move'] === '1' && ($this->checkUserRole($this->options['move_files_role']) || $this->checkUserRole($this->options['move_folders_role']))) {
          wp_enqueue_script('jquery-ui-droppable');
          wp_enqueue_script('jquery-ui-draggable');
        }

        wp_enqueue_script('jquery-effects-core');
        wp_enqueue_script('jquery-effects-fade');
        wp_enqueue_script('collagePlus');
        wp_enqueue_script('removeWhitespace');
        wp_enqueue_style('ilightbox');
        wp_enqueue_style('ilightbox-skin');
        wp_enqueue_script('jquery.requestAnimationFrame');
        wp_enqueue_script('jquery.mousewheel');
        wp_enqueue_script('ilightbox');
        wp_enqueue_script('imagesloaded');
        wp_enqueue_script('qtip');
        break;
      case 'mediaplayer':
        wp_enqueue_script('imagesloaded');
        wp_enqueue_style('UseyourDrive.Media');
        wp_enqueue_script('jQuery.jplayer');
        wp_enqueue_script('jQuery.jplayer.playlist');
        wp_enqueue_script('UseyourDrive.Media');
        break;
      case 'upload':
        wp_enqueue_style('UseyourDrive-fileupload-jquery-ui');
        wp_enqueue_script('jquery-ui-droppable');
        wp_enqueue_script('jquery-ui-button');
        wp_enqueue_script('jquery-ui-progressbar');
        wp_enqueue_script('jQuery.iframe-transport');
        wp_enqueue_script('jQuery.fileupload');
        wp_enqueue_script('jQuery.fileupload-process');
        break;
    }

    $this->_loadscripts[$template] = true;
  }

  protected function setLastPath($path) {
    $this->_lastPath = $path;
    if ($this->_lastPath === '') {
      $this->_lastPath = null;
    }
    return $this->_lastPath;
  }

  protected function removeDeprecatedOptions($options = array()) {
    /* Deprecated Shuffle, v1.3 */
    if (isset($options['shuffle'])) {
      unset($options['shuffle']);
      $options['sortfield'] = 'shuffle';
    }
    /* Changed Userfolders, v1.3 */
    if (isset($options['userfolders']) && $options['userfolders'] === '1') {
      $options['userfolders'] = 'auto';
    }

    if (isset($options['partiallastrow'])) {
      unset($options['partiallastrow']);
    }

    /* Changed Rename/Delete/Move Folders & Files v1.5.2 */
    if (isset($options['move_role'])) {
      $options['move_files_role'] = $options['move_role'];
      $options['move_folders_role'] = $options['move_role'];
      unset($options['move_role']);
    }

    if (isset($options['rename_role'])) {
      $options['rename_files_role'] = $options['rename_role'];
      $options['rename_folders_role'] = $options['rename_role'];
      unset($options['rename_role']);
    }

    if (isset($options['delete_role'])) {
      $options['delete_files_role'] = $options['delete_role'];
      $options['delete_folders_role'] = $options['delete_role'];
      unset($options['delete_role']);
    }

    /* Changed 'ext' to 'include_ext' v1.5.2 */
    if (isset($options['ext'])) {
      $options['include_ext'] = $options['ext'];
      unset($options['ext']);
    }

    return $options;
  }

  protected function updateLists() {


    $this->lists[$this->listtoken] = $this->options;
    $this->_cleanLists();
    update_option('use_your_drive_lists', $this->lists);
  }

  protected function sortFilelist($foldercontents) {

    if (count($foldercontents) > 0) {
// Sort Filelist, folders first
      $sort = array();

      $sort_field = 'name';
      $sort_order = SORT_ASC;

      if (isset($_REQUEST['sort'])) {
        $sort_options = explode(':', $_REQUEST['sort']);

        if ($sort_options[0] === 'shuffle') {
          shuffle($foldercontents);
          return $foldercontents;
        }

        if (count($sort_options) === 2) {

          switch ($sort_options[0]) {
            case 'name':
              $sort_field = 'name';
              break;
            case 'size':
              $sort_field = 'size';
              break;
            case 'modified':
              $sort_field = 'edited';
              break;
          }

          switch ($sort_options[1]) {
            case 'asc':
              $sort_order = SORT_ASC;
              break;
            case 'desc':
              $sort_order = SORT_DESC;
              break;
          }
        }
      }

      foreach ($foldercontents as $k => $v) {
        $sort['is_dir'][$k] = $v['is_dir'];
        $sort['sort'][$k] = strtolower($v[$sort_field]);
      }

      /* Sort by dir desc and then by name asc */
      if (version_compare(PHP_VERSION, '5.4.0') >= 0) {
        @array_multisort($sort['is_dir'], SORT_DESC, SORT_REGULAR, $sort['sort'], $sort_order, SORT_NATURAL, $foldercontents, SORT_ASC, SORT_NATURAL);
      } else {
        array_multisort($sort['is_dir'], SORT_DESC, $sort['sort'], $sort_order, $foldercontents);
      }
    }
    return $foldercontents;
  }

  protected function createUserFolder($users = array()) {
    /* Create unique user path
      Needed if $userfolders is set */
    $folderstocreate = array();

    if (count($users) > 0) {
      foreach ($users as $user) {
        $folderstocreate[] = strtr($this->settings['userfolder_name'], array(
          "%user_login%" => $user->user_login,
          "%user_email%" => $user->user_email,
          "%user_firstname%" => $user->user_firstname,
          "%user_lastname%" => $user->user_lastname,
          "%display_name%" => $user->display_name,
          "%ID%" => $user->ID
        ));
      }
      $result = $this->addUserFolders($folderstocreate);
      return $result;
    }

    if (is_user_logged_in()) {
      $current_user = wp_get_current_user();

      $folderstocreate = strtr($this->settings['userfolder_name'], array(
        "%user_login%" => $current_user->user_login,
        "%user_email%" => $current_user->user_email,
        "%user_firstname%" => $current_user->user_firstname,
        "%user_lastname%" => $current_user->user_lastname,
        "%display_name%" => $current_user->display_name,
        "%ID%" => $current_user->ID
      ));
    } else {
      $userfolder = uniqid();
      if (!isset($_COOKIE['UYD-ID'])) {
        $expire = time() + 60 * 60 * 24 * 7;
        setcookie('UYD-ID', $userfolder, $expire, '/');
      } else {
        $userfolder = $_COOKIE['UYD-ID'];
      }

      $userhash = md5($userfolder);
      $folderstocreate = __('Guest', 'useyourdrive') . ' - ' . $userhash;
    }

    /* Add folder if needed */
    $result = $this->addUserFolder($folderstocreate);

    if (!is_wp_error($result) && $result !== false) {
      $this->_userFolder = $result;
      return $this->_userFolder->getId();
    }

    return $this->options['root'];
  }

  public function userChangeFolder($listoptions, $userfoldername, $oldfoldername, $delete = false) {
    if ($this->_isAuthorized(true) === true) {
      if ($userfoldername !== '' && $oldfoldername !== '') {
        $this->updateUserFolder($listoptions, $userfoldername, $oldfoldername, $delete);
      }
    }
  }

  protected function sendNotificationEmail($emailtype = false, $entries = array()) {

    if ($emailtype === false) {
      return;
    }

    /* Get emailaddress */
    $recipients = strtr(trim($this->options['notificationemail']), array(
      "%admin_email%" => get_site_option('admin_email')
    ));

    /* Current site url */
    $currenturl = $_SERVER['HTTP_REFERER'];

    /* Vistor name and email */
    $visitor = __('A guest', 'useyourdrive');
    if (is_user_logged_in()) {
      $current_user = wp_get_current_user();
      $visitor = $current_user->display_name;

      $recipients = strtr($recipients, array(
        "%user_email%" => $current_user->user_email
      ));
    }

    /* User IP */
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
      //check ip from share internet 
      $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
      //to check ip is pass from proxy 
      $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
      $ip = $_SERVER['REMOTE_ADDR'];
    }
    $ip = apply_filters('wpb_get_ip', $ip);


    /* Subject */
    $subject = get_bloginfo();

    /* Create FileList */
    $_filelisttemplate = trim($this->settings['filelist_template']);
    $filelist = '';
    foreach ($entries as $cachedentry) {
      $entry = $cachedentry->getItem();
      $fileline = strtr($_filelisttemplate, array(
        "%filename%" => $entry->getTitle(),
        "%filesize%" => UseyourDrive_bytesToSize1024($entry->getFileSize()),
        "%fileurl%" => $entry->getAlternateLink(),
        "%filepath%" => $cachedentry->getPath($this->_rootFolder)
      ));
      $filelist .= $fileline;
    }

    /* Create Message */
    switch ($emailtype) {
      case 'download':
        if (count($entries) === 1) {
          $subject .= ' | ' . __('File downloaded', 'useyourdrive') . ': ' . $entry->getTitle();
        } else {
          $subject .= ' | ' . __('Files downloaded', 'useyourdrive') . ' (' . count($entries) . ')';
        }
        $message = trim($this->settings['download_template']);
        break;
      case 'upload':
        $subject .= ' | ' . __('New file(s) on Google Drive', 'useyourdrive');
        $message = trim($this->settings['upload_template']);
        break;
      case 'deletion':
      case 'deletion_multiple':
        if (count($entries) === 1) {
          $subject .= ' | ' . __('File deleted on Google Drive', 'useyourdrive');
        } else {
          $subject .= ' | ' . __('Files deleted on Google Drive', 'useyourdrive') . ' (' . count($entries) . ')';
        }

        $message = trim($this->settings['delete_template']);
        break;
    }

    /* Geo location if required */
    $location = '-location unknown-';
    if (strpos($message, '%location%') !== false) {
      try {
        $geolocation = (unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip=' . $ip)));

        if ($geolocation !== false && $geolocation['geoplugin_status'] === 200) {
          $location = $geolocation['geoplugin_city'] . ', ' . $geolocation['geoplugin_region'] . ', ' . $geolocation['geoplugin_countryName'];
        } else {
          $location = '-location unknown-';
        }
      } catch (Exception $ex) {
        $location = '-location unknown-';
      }
    }


    /* Replace filters */
    $message = strtr($message, array(
      "%visitor%" => $visitor,
      "%currenturl%" => $currenturl,
      "%filelist%" => $filelist,
      "%ip%" => $ip,
      "%location%" => $location
    ));


    $recipients = explode(',', $recipients);

    /* Create Notifaction variable for hook */
    $notification = array(
      'type' => $emailtype,
      'recipients' => $recipients,
      'subject' => $subject,
      'message' => $message,
      'files' => $entries
    );

    /* Executes hook */
    $notification = apply_filters('useyourdrive_notification', $notification);

    /* Send mail */
    try {
      $headers = array('Content-Type: text/html; charset=UTF-8');
      $htmlmessage = nl2br($notification['message']);

      foreach ($notification['recipients'] as $recipient) {
        $result = wp_mail($recipient, $notification['subject'], $htmlmessage, $headers);
      }
    } catch (Exception $ex) {
      
    }
  }

  private function _cleanLists() {
    $now = time();
    foreach ($this->lists as $token => $list) {

      if (!isset($list['expire']) || ($list['expire']) < $now) {
        unset($this->lists[$token]);
      }
    }
  }

  private function _gZipCompression() {
    /* Compress file list if possible */
    if ($this->settings['gzipcompression'] === 'Yes') {
      $zlib = (ini_get('zlib.output_compression') == '' || !ini_get('zlib.output_compression')) && (ini_get('output_handler') != 'ob_gzhandler');
      if ($zlib === true) {
        if (extension_loaded('zlib')) {
          if (!in_array('ob_gzhandler', ob_list_handlers())) {
            ob_start('ob_gzhandler');
          }
        }
      }
    }
  }

  private function _isAuthorized($hook = false) {
    if (isset($_REQUEST['action']) && ($hook === false)) {
      switch ($_REQUEST['action']) {
        case 'useyourdrive-upload-file':
        case 'useyourdrive-get-filelist':
        case 'useyourdrive-get-gallery':
        case 'useyourdrive-get-playlist':
        case 'useyourdrive-rename-entry':
        case 'useyourdrive-move-entry':
        case 'useyourdrive-edit-description-entry':
        case 'useyourdrive-add-folder':
        case 'useyourdrive-create-zip':
          check_ajax_referer($_REQUEST['action']);
          break;
        case 'useyourdrive-delete-entry':
        case 'useyourdrive-delete-entries':
          check_ajax_referer('useyourdrive-delete-entry');
          break;
        case 'useyourdrive-create-link':
        case 'useyourdrive-embedded':
          check_ajax_referer('useyourdrive-create-link');
          break;
        case 'useyourdrive-download':
        case 'useyourdrive-preview':
        case 'useyourdrive-getpopup':
        case 'useyourdrive-revoke':
          break;
        default:
          die();
      }
    }

    $hasToken = $this->loadToken();

    if (is_wp_error($hasToken)) {
      return $hasToken;
    }

    if (is_wp_error($appInfo = $this->setAppConfig())) {
      return $appInfo;
    }

    $client = $this->startClient();
    return true;
  }

  /**
   * Checks if a particular user has a role.
   * Returns true if a match was found.
   *
   * @param array $roles Roles array.
   * @return bool
   */
  public function checkUserRole($roles_to_check = array()) {

    if (in_array('all', $roles_to_check)) {
      return true;
    }

    if (in_array('none', $roles_to_check)) {
      return false;
    }

    if (in_array('guest', $roles_to_check)) {
      return true;
    }

    if (is_super_admin()) {
      return true;
    }

    if (!is_user_logged_in()) {
      return false;
    }

    $user = wp_get_current_user();

    if (empty($user) || (!($user instanceof WP_User))) {
      return false;
    }

    foreach ($user->roles as $role) {
      if (in_array($role, $roles_to_check)) {
        return true;
      }
    }

    return false;
  }

  public function removeElementWithValue($array, $key, $value) {
    foreach ($array as $subKey => $subArray) {
      if ($subArray[$key] == $value) {
        unset($array[$subKey]);
      }
    }

    return $array;
  }

}
