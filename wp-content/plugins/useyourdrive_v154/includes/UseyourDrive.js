var _activeDrive = false,
        _refreshDrivetimer,
        _updateDrivetimer,
        _resizeDriveTimer = null,
        _thumbDriveTimer = null,
        readGDriveArrCheckBoxes,
        uyd_playlists = {},
        _GDcache = {},
        _Driveuploads = {},
        mobile = false,
        windowwidth;

var GD_iLightbox = false;

jQuery(document).ready(function ($) {
  $(window).load(function () {
    'use strict';

    /* Check if user is using a mobile device, alters opening documents*/
    if (/Android|webOS|iPhone|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
      var userAgent = navigator.userAgent.toLowerCase();
      if ((userAgent.search("android") > -1) && (userAgent.search("mobile") > -1)) {
        mobile = true;
      } else if ((userAgent.search("android") > -1) && !(userAgent.search("mobile") > -1)) {
        mobile = false;
      } else {
        mobile = true;
      }
    }

    /* Simple check if browser is Chrome > allow folder uploads */
    var is_chrome = navigator.userAgent.toLowerCase().indexOf('chrome') > -1;

    /* Check if user is using a mobile device (including tables) detected by WordPress, alters css*/
    if (UseyourDrive_vars.is_mobile === '1') {
      $('html').addClass('uyd-mobile');
    }

    $(".UseyourDrive img.preloading").not('.hidden').unveil(200, null, function () {
      $(this).load(function () {
        $(this).removeClass('preloading');
      });
    });

    refreshDriveLists();
    //Refresh lists every 15 minutes
    _refreshDrivetimer = setInterval(refreshDriveLists, 1000 * 60 * 15);

    //Remove no JS message
    $(".UseyourDrive.jsdisabled").removeClass('jsdisabled');

    //Add return to home event to nav-home
    $('.UseyourDrive .nav-home').click(function () {
      var listtoken = $(this).closest(".UseyourDrive").attr('data-token'),
              orgid = $(this).closest(".UseyourDrive").attr('data-org-id'),
              data = {listtoken: listtoken
              };
      $(".UseyourDrive[data-qtip-id='search-" + listtoken + "'] .search-input").val('');
      $(this).closest(".UseyourDrive").attr('data-id', orgid);
      $(this).closest(".UseyourDrive").attr('data-path', '');
      getDriveFileList(data);
    });

    //Add refresh event to nav-refresh
    $('.UseyourDrive .nav-refresh').click(function () {
      var listtoken = $(this).closest(".UseyourDrive").attr('data-token'),
              data = {
                listtoken: listtoken
              };
      $(".UseyourDrive[data-qtip-id='search-" + listtoken + "'] .search-input").val('');
      getDriveFileList(data, 'hardrefresh');
    });

    //Add scroll event to nav-upload
    $('.UseyourDrive .nav-upload').click(function () {
      $('.qtip.UseyourDrive').qtip('hide');
      var listtoken = $(this).closest(".gear-menu").attr('data-token'),
              uploadcontainer = $(".UseyourDrive[data-token='" + listtoken + "']").find('.fileupload-container');
      $('html, body').animate({
        scrollTop: uploadcontainer.offset().top
      }, 1500);
      for (var i = 0; i < 3; i++) {
        uploadcontainer.find('.fileupload-buttonbar').fadeTo('slow', 0.5).fadeTo('slow', 1.0);
      }
    });

    /* Add layout change event */
    $('.UseyourDrive.files .nav-layout').click(function () {
      var listtoken = $(this).closest(".gear-menu").attr('data-token');

      if ($(".UseyourDrive[data-token='" + listtoken + "']").attr('data-layout') === 'list') {
        $(".UseyourDrive[data-token='" + listtoken + "']").attr('data-layout', 'grid');
      } else {
        $(".UseyourDrive[data-token='" + listtoken + "']").attr('data-layout', 'list');
      }

      $('.qtip.UseyourDrive').qtip('hide');
      var data = {
        listtoken: listtoken
      };
      getDriveFileList(data);
    });

    /* Add Link to event*/
    $('#UseyourDrive-UserToFolder .uyd-linkbutton').click(function () {
      $('#UseyourDrive-UserToFolder .thickbox_opener').removeClass("thickbox_opener");
      $(this).parent().addClass("thickbox_opener");
      $(this).parent().find('.uyd-unlinkbutton').removeClass("disabled");
      tb_show("(Re) link to folder", '#TB_inline?height=450&amp;width=800&amp;inlineId=uyd-embedded');
    });

    $('#UseyourDrive-UserToFolder .uyd-unlinkbutton').click(function () {
      var curbutton = $(this),
              user_id = curbutton.parent().attr('data-userid');

      $.ajax({type: "POST",
        url: UseyourDrive_vars.ajax_url,
        data: {
          action: 'useyourdrive-unlinkusertofolder',
          userid: user_id,
          _ajax_nonce: UseyourDrive_vars.createlink_nonce
        },
        beforeSend: function () {
          curbutton.addClass('disabled');
        },
        success: function (response) {
          if (response === '1') {
            curbutton.parent().find('.uyd-linkedto').html(UseyourDrive_vars.str_nolink);
          } else {
            curbutton.removeClass("disabled");
          }
        },
        dataType: 'text'
      });

    });

    /* Delete files event */
    $(".UseyourDrive .selected-files-delete").click(function () {
      var listtoken = $(this).closest(".gear-menu").attr('data-token');
      $('.qtip.UseyourDrive').qtip('hide');

      var entries = readGDriveArrCheckBoxes(".UseyourDrive[data-token='" + listtoken + "'] input[name='selected-files[]']");

      if (entries.length > 0) {
        var dialog_html = $("<div class='dialog' title='" + UseyourDrive_vars.str_delete_title + "'><p>" + UseyourDrive_vars.str_delete_multiple + "</p></div>");
        var l18nButtons = {};
        l18nButtons[UseyourDrive_vars.str_delete_title] = function () {
          var data = {
            action: 'useyourdrive-delete-entries',
            entries: entries,
            listtoken: listtoken,
            _ajax_nonce: UseyourDrive_vars.delete_nonce
          };
          changeDriveEntry(data);
          $(this).dialog("destroy");
        };
        l18nButtons[UseyourDrive_vars.str_cancel_title] = function () {
          $(this).dialog("destroy");
        };
        dialog_html.dialog({
          dialogClass: 'UseyourDrive',
          resizable: false,
          height: 200,
          width: 400,
          modal: true,
          buttons: l18nButtons
        });
      }
      return false;
    });

    function updateLayoutFilelist(listtoken) {
      var filelist = $(".UseyourDrive[data-token='" + listtoken + "'].files");
      if (filelist.length === 0) {
        return;
      }
      if ((filelist).attr('data-layout') === 'list') {
        $(".UseyourDrive[data-token='" + listtoken + "']").removeClass('uyd-grid').addClass('uyd-list');
        $(".qtip[data-qtip-id='nav-" + listtoken + "']").find('.fa-th-large').closest('li').show();
        $(".qtip[data-qtip-id='nav-" + listtoken + "']").find('.fa-th-list').closest('li').hide();
      } else {
        $(".UseyourDrive[data-token='" + listtoken + "']").removeClass('uyd-list').addClass('uyd-grid');
        $(".qtip[data-qtip-id='nav-" + listtoken + "']").find('.fa-th-large').closest('li').hide();
        $(".qtip[data-qtip-id='nav-" + listtoken + "']").find('.fa-th-list').closest('li').show();

        /* Update items to fit in viewport */
        var targetwidth = 150,
                filelistwidth = $(".UseyourDrive[data-token='" + listtoken + "'] .files.layout-grid").innerWidth(),
                itemsonrow = Math.ceil(filelistwidth / targetwidth),
                calculatedwidth = Math.floor(filelistwidth / itemsonrow);

        $(".UseyourDrive[data-token='" + listtoken + "'] .layout-grid").removeWhitespace();
        $(".UseyourDrive[data-token='" + listtoken + "'] .entry_block").each(function () {
          var padding = parseInt($(this).css('padding-left')) + parseInt($(this).css('padding-right'));
          $(this).outerWidth(calculatedwidth - padding);
        });
        $(".UseyourDrive[data-token='" + listtoken + "'] .layout-grid").fadeTo(0, 0).delay(500).fadeTo(1000, 1);
      }
    }

    /* Settings menu */
    $('.UseyourDrive .nav-gear').each(function () {
      var listtoken = $(this).closest(".UseyourDrive").attr('data-token');

      $(this).qtip({
        prerender: true,
        id: 'nav-' + listtoken,
        content: {
          text: $(this).next('.gear-menu')
        },
        position: {
          my: 'top right',
          at: 'bottom center',
          target: $(this).find('i'),
          viewport: $(window),
          adjust: {
            scroll: false
          }
        },
        style: {
          classes: 'UseyourDrive qtip-light'
        },
        show: {
          event: 'click, mouseenter',
          solo: true
        },
        hide: {
          event: 'mouseleave unfocus',
          fixed: true,
          delay: 200
        },
        events: {
          show: function (event, api) {
            var selectedboxes = readGDriveArrCheckBoxes(".UseyourDrive[data-token='" + listtoken + "'] input[name='selected-files[]']");
            if (($(".UseyourDrive[data-qtip-id='search-" + listtoken + "'] .search-input").length > 0) && $(".UseyourDrive[data-qtip-id='search-" + listtoken + "'] .search-input").val() !== '') {
              api.elements.content.find(".all-files-to-zip").parent().hide();
            } else {
              api.elements.content.find(".all-files-to-zip").parent().show();
            }

            if (selectedboxes.length === 0) {
              api.elements.content.find(".selected-files-to-zip").parent().hide();
              api.elements.content.find(".selected-files-delete").parent().hide();
            } else {
              api.elements.content.find(".selected-files-to-zip").parent().show();
              api.elements.content.find(".selected-files-delete").parent().show();
            }

            var visibleelements = api.elements.content.find('ul > li').not('.gear-menu-no-options').filter(function () {
              return $(this).css('display') !== 'none';
            });

            if (visibleelements.length > 0) {
              api.elements.content.find('.gear-menu-no-options').hide();
            } else {
              api.elements.content.find('.gear-menu-no-options').show();
            }

          }
        }
      });
    });

    // Searchbox
    $('.UseyourDrive .nav-search').each(function () {
      var listtoken = $(this).closest(".UseyourDrive").attr('data-token');

      $(this).qtip({
        prerender: true,
        id: 'search-' + listtoken,
        content: {
          text: $(this).next('.search-div'),
          button: $(this).next('.search-div').find('.search-remove')
        },
        position: {
          my: 'top right',
          at: 'bottom center',
          target: $(this).find('i'),
          viewport: $(window),
          adjust: {
            scroll: false
          }
        }, style: {
          classes: 'UseyourDrive search qtip-light'
        },
        show: {
          effect: function () {
            $(this).fadeTo(90, 1, function () {
              $('input', this).focus();
            });
          }
        },
        hide: {
          fixed: true, delay: 1500
        }
      });
    });

    $('.UseyourDrive .search-input').each(function () {
      $(this).on("keyup", function (event) {
        var listtoken = $(this).closest(".UseyourDrive").attr('data-qtip-id').replace('search-', '');
        clearTimeout(_updateDrivetimer);
        var data = {
          listtoken: listtoken
        };
        _updateDrivetimer = setTimeout(function () {
          getDriveFileList(data);
        }, 1000);
        if ($(this).val().length > 0) {
          $(".UseyourDrive[data-token='" + listtoken + "'] .loading").addClass('search');
          $(".UseyourDrive[data-token='" + listtoken + "'] .nav-search").addClass('inuse');
        } else {
          $(".UseyourDrive[data-token='" + listtoken + "'] .nav-search").removeClass('inuse');
        }
      });
    });
    $('.UseyourDrive .search-remove').click(function () {
      if ($(this).parent().find('.search-input').val() !== '') {
        $(this).parent().find('.search-input').val('');
        $(this).parent().find('.search-input').trigger('keyup');
      }
    });
    //Sortable column Names
    $(".UseyourDrive .sortable").click(function () {

      var listtoken = $(this).closest(".UseyourDrive").attr('data-token');
      var newclass = 'asc';
      if ($(this).hasClass('asc')) {
        newclass = 'desc';
      }

      $(".UseyourDrive[data-token='" + listtoken + "'] .sortable").removeClass('asc').removeClass('desc');
      $(this).addClass(newclass);
      var sortstr = $(this).attr('data-sortname') + ':' + newclass;
      $(".UseyourDrive[data-token='" + listtoken + "']").attr('data-sort', sortstr);
      var data = {
        listtoken: listtoken
      };
      clearTimeout(_updateDrivetimer);
      _updateDrivetimer = setTimeout(function () {
        getDriveFileList(data);
      }, 300);
    });

    //To ZIP
    $('.select-all-files').click(function () {
      $(this).closest(".UseyourDrive").find(".selected-files:checkbox").prop("checked", $(this).prop("checked"));
      if ($(this).prop("checked") === true) {
        $(this).closest(".UseyourDrive").find(".selected-files:checkbox").show();
      } else {
        $(this).closest(".UseyourDrive").find(".selected-files:checkbox").hide();
      }
    });

    $(".UseyourDrive .all-files-to-zip, .UseyourDrive .selected-files-to-zip").click(function (event) {
      var location = UseyourDrive_vars.ajax_url,
              listtoken = $(this).closest(".gear-menu").attr('data-token'),
              lastFolder = $(".UseyourDrive[data-token='" + listtoken + "']").attr('data-id'),
              data = {
                action: 'useyourdrive-create-zip',
                listtoken: listtoken,
                lastFolder: lastFolder,
                _ajax_nonce: UseyourDrive_vars.createzip_nonce
              };
      if ($(event.target).hasClass('selected-files-to-zip')) {
        data.files = readGDriveArrCheckBoxes(".UseyourDrive[data-token='" + listtoken + "'] input[name='selected-files[]']");
      }

      $('.qtip.UseyourDrive').qtip('hide');
      $(this).attr('href', location + "?" + $.param(data));

      return;
    });

    function isDriveCached(identifyer, listtoken) {
      if (typeof _GDcache[listtoken] === 'undefined') {
        _GDcache[listtoken] = {};
      }

      if (typeof _GDcache[listtoken][identifyer] === 'undefined' || $.isEmptyObject(_GDcache[listtoken][identifyer])) {
        return false;
      } else {

        var unixtime = Math.round((new Date()).getTime() / 1000);
        if (_GDcache[listtoken][identifyer].expires < unixtime) {
          _GDcache[listtoken][identifyer] = {};
          return false;
        }
        return _GDcache[listtoken][identifyer];
      }
    }

    function updateDriveDiv(response, identifyer, listtoken) {
      $(".UseyourDrive[data-token='" + listtoken + "'] .loading").fadeTo(400, 1);

      if (typeof _GDcache[listtoken] === 'undefined') {
        _GDcache[listtoken] = {};
      }

      _GDcache[listtoken][identifyer] = response;

      $(".UseyourDrive[data-token='" + listtoken + "'] .ajax-filelist").html(response.html);
      $(".UseyourDrive[data-token='" + listtoken + "'] .nav-title").html(response.breadcrumb);
      $(".UseyourDrive[data-token='" + listtoken + "'] .current-folder-raw").text(response.rawpath);
      if (response.lastFolder !== null) {
        $(".UseyourDrive[data-token='" + listtoken + "']").attr('data-id', response.lastFolder);
      }
      if (response.folderPath !== null) {
        $(".UseyourDrive[data-token='" + listtoken + "']").attr('data-path', response.folderPath);
      }

      $(".UseyourDrive[data-token='" + listtoken + "'] .loading").fadeOut(400);

      updateDriveActions(listtoken);
    }

    function getDriveFileList(data, hardrefresh) {
      if (_refreshDrivetimer) {
        clearInterval(_refreshDrivetimer);
      }

      _refreshDrivetimer = setInterval(refreshDriveLists, 1000 * 60 * 15);
      var listtoken = data.listtoken,
              list = $(".UseyourDrive[data-token='" + listtoken + "']").attr('data-list'),
              lastFolder = $(".UseyourDrive[data-token='" + listtoken + "']").attr('data-id'),
              folderPath = $(".UseyourDrive[data-token='" + listtoken + "']").attr('data-path'),
              sort = $(".UseyourDrive[data-token='" + listtoken + "']").attr('data-sort'),
              query = $(".UseyourDrive[data-qtip-id='search-" + listtoken + "'] .search-input").val(),
              ajax_action = 'useyourdrive-get-filelist',
              nonce = UseyourDrive_vars.refresh_nonce;

      if (list === 'gallery') {
        ajax_action = 'useyourdrive-get-gallery';
        nonce = UseyourDrive_vars.gallery_nonce;
      } else {
        data.filelayout = $(".UseyourDrive[data-token='" + listtoken + "']").attr('data-layout');
      }

      if (typeof query !== 'undefined' && query.length > 2 && query !== 'Search filenames') {
        data.query = query;
      }

      if (typeof data.id === 'undefined') {
        data.id = lastFolder;
      }

      if (typeof hardrefresh !== 'undefined') {
        _GDcache = [];
      }

      data.sort = sort;
      data.action = ajax_action;
      data.mobile = mobile;
      data._ajax_nonce = nonce;

      var str = JSON.stringify(data);
      var identifyer = str.hashCode();
      var request = false;

      request = isDriveCached(identifyer, listtoken);

      if (request !== false) {
        return updateDriveDiv(request, identifyer, listtoken);
      }

      /* Don't add in the identifyer */
      if (typeof hardrefresh !== 'undefined') {
        data.hardrefresh = true;
      }

      data.lastFolder = lastFolder;
      data.folderPath = folderPath;

      $.ajax({
        type: "POST",
        url: UseyourDrive_vars.ajax_url,
        data: data, beforeSend: function () {
          $(".UseyourDrive[data-token='" + listtoken + "'] .no_results").remove();
          $(".UseyourDrive[data-token='" + listtoken + "'] .loading").removeClass('initialize upload error');
          $(".UseyourDrive[data-token='" + listtoken + "'] .loading").fadeTo(400, 1);
        },
        complete: function () {
          $(".UseyourDrive[data-token='" + listtoken + "'] .loading").removeClass('search');
        },
        success: function (response) {
          if (response !== null && response !== 0) {
            updateDriveDiv(response, identifyer, listtoken);
          } else {
            $(".UseyourDrive[data-token='" + listtoken + "'] .nav-title").html(UseyourDrive_vars.str_no_filelist);
            $(".UseyourDrive[data-token='" + listtoken + "'] .loading").addClass('error');
            updateDriveActions(listtoken);
          }
        },
        error: function () {
          $(".UseyourDrive[data-token='" + listtoken + "'] .nav-title").html(UseyourDrive_vars.str_no_filelist);
          $(".UseyourDrive[data-token='" + listtoken + "'] .loading").addClass('error');
          updateDriveActions(listtoken);
        },
        dataType: 'json'});
    }

    function changeDriveEntry(data) {
      var listtoken = data.listtoken,
              lastFolder = $(".UseyourDrive[data-token='" + listtoken + "']").attr('data-id');
      data.lastFolder = lastFolder;
      $.ajax({type: "POST",
        url: UseyourDrive_vars.ajax_url,
        data: data, beforeSend: function () {
          $(".UseyourDrive[data-token='" + listtoken + "'] .loading").fadeTo(400, 1);
        },
        complete: function () {
          var data = {
            listtoken: listtoken
          };
          _GDcache[listtoken] = {};
          getDriveFileList(data, 'hardrefresh');
        }, success: function (response) {
          if (typeof response !== 'undefined') {
            if (typeof response.result !== 'undefined' && response.result !== '1') {
              var dialog_html = $("<div class='dialog' title='" + UseyourDrive_vars.str_error_title + "'><p>" + response.msg + "</em></p></div>");
              var l18nButtons = {};
              l18nButtons[UseyourDrive_vars.str_close_title] = function () {
                $(this).dialog("close");
              };
              dialog_html.dialog({
                dialogClass: 'UseyourDrive',
                resizable: false, height: 200,
                width: 400,
                modal: true,
                buttons: l18nButtons
              });
            } else {
              if (response.lastFolder !== null) {
                $(".UseyourDrive[data-token='" + listtoken + "']").attr('data-id', response.lastFolder);
              }
            }
          }
        },
        dataType: 'json'
      });
    }

    function refreshDriveLists() {
      var selector = $('.UseyourDrive.files, .UseyourDrive.gridgallery');
      if (_activeDrive) {
        var selector = $('.UseyourDrive.files');
      }

//Create file lists
      selector.each(function () {

        var listtoken = $(this).attr('data-token'),
                data = {
                  listtoken: listtoken
                };
        getDriveFileList(data);
      });
      _activeDrive = true;
    }

    window.updateDriveCollage = function updateDriveCollage(listtoken) {
      var selector = $(".UseyourDrive.gridgallery[data-token='" + listtoken + "']");
      //Set Image container explicit
      var padding = parseInt($(selector).find(".image-collage").css('padding-left')) + parseInt($(selector).find(".image-collage").css('padding-right'));
      var containerwidth = $(selector).width() - padding - 1;
      $(selector).find(".image-collage").outerWidth(containerwidth);

      var targetheight = $(selector).attr('data-targetheight');
      $(selector).find('.image-collage').removeWhitespace().collagePlus({
        'targetHeight': targetheight,
        'fadeSpeed': "slow",
        'allowPartialLastRow': true
      });

      $(selector).find(".image-container.hidden").fadeOut(0);
      $(selector).find(".image-collage").fadeTo(1500, 1);

      $(selector).find(".image-container").each(function () {
        $(this).find(".folder-thumb").width($(this).width()).height($(this).height());
      });

      $(selector).find('.image-folder-img').delay(1000).animate({opacity: 0}, 1500);
      if (_thumbDriveTimer) {
        clearInterval(_thumbDriveTimer);
      }

      updateDriveImageFolders();
      _thumbDriveTimer = setInterval(updateDriveImageFolders, 15000);
    };

    function updateDriveImageFolders() {
      $(".UseyourDrive.gridgallery .image-folder").each(function () {
        $(this).find('.folder-thumb').fadeIn(1500);
        var delay = Math.floor(Math.random() * 3000) + 1500;
        $(this).find(".thumb3").delay(delay).fadeOut(1500);
        $(this).find(".thumb2").delay(delay + 1500).delay(delay).fadeOut(1500);
        $(this).find(".thumb3").delay(2 * (delay + 1500)).delay(delay).fadeIn(1500);
      });
    }

    function updateDriveActions(listtoken) {
      updateLayoutFilelist(listtoken);

      $(".UseyourDrive[data-token='" + listtoken + "'] .entry").unbind('hover');
      $(".UseyourDrive[data-token='" + listtoken + "'] .entry").hover(
              function () {
                $(this).addClass('hasfocus');
              },
              function () {
                $(this).removeClass('hasfocus');
              }
      );

      $(".UseyourDrive[data-token='" + listtoken + "'] .entry").on('mouseover', function () {
        $(this).addClass('hasfocus');
      });

      $(".UseyourDrive[data-token='" + listtoken + "'] .entry").unbind('click');
      $(".UseyourDrive[data-token='" + listtoken + "'] .entry").click(function () {
        $(this).find('.entry_checkbox input[type="checkbox"]').trigger('click');
      });

      /* Thumbnails */
      $(".UseyourDrive[data-token='" + listtoken + "'] .entry[data-tooltip] .entry_name, .UseyourDrive[data-token='" + listtoken + "'] .entry[data-tooltip] .entry_lastedit").each(function () {
        $(this).qtip({
          content: {
            text: $(this).parent().find('.description_textbox')
          },
          position: {
            target: 'mouse',
            adjust: {x: 5, y: 5, scroll: false},
            viewport: $(".UseyourDrive[data-token='" + listtoken + "']")
          },
          show: {
            delay: 500,
            solo: true
          },
          hide: {
            event: 'click mouseleave unfocus'
          },
          style: {
            classes: 'UseyourDrive description qtip-light'
          }
        });
      });


      /* Edit menu popup */
      $(".UseyourDrive[data-token='" + listtoken + "'] .entry .entry_edit_menu").each(function () {
        $(this).click(function (e) {
          e.stopPropagation();
        });

        $(this).qtip({
          content: {
            text: $(this).next('.uyd-dropdown-menu')
          },
          position: {
            my: 'top center',
            at: 'bottom center',
            target: $(this),
            scroll: false,
            viewport: $(".UseyourDrive[data-token='" + listtoken + "']")
          },
          show: {
            event: 'click',
            solo: true
          },
          hide: {
            event: 'mouseleave unfocus',
            delay: 200,
            fixed: true
          },
          events: {
            show: function (event, api) {
              api.elements.target.closest('.entry').addClass('hasfocus').addClass('popupopen');
            },
            hide: function (event, api) {
              api.elements.target.closest('.entry').removeClass('hasfocus').removeClass('popupopen');
            }
          },
          style: {
            classes: 'UseyourDrive qtip-light'
          }
        });
      });

      /* Description popup */
      $(".UseyourDrive[data-token='" + listtoken + "'] .entry .entry_description").each(function () {
        $(this).click(function (e) {
          e.stopPropagation();
        });

        $(this).qtip({
          content: {
            text: $(this).next('.description_textbox')
          },
          position: {
            my: 'top center',
            at: 'bottom center',
            target: $(this),
            scroll: false,
            viewport: $(".UseyourDrive[data-token='" + listtoken + "']")
          },
          show: {
            delay: 200,
            solo: true
          },
          hide: {
            event: 'mouseleave unfocus',
            delay: 200,
            fixed: true
          },
          events: {
            show: function (event, api) {
              api.elements.target.closest('.entry').addClass('hasfocus').addClass('popupopen');
            },
            hide: function (event, api) {

              if (api.elements.content.find('.description_textarea').length > 0) {
                var html = api.elements.content.find('.description_textarea').val().replace(/\r\n|\r|\n/g, "<br />");
                var viewableText = $("<div>").addClass('description_text');
                viewableText.html(html);
                api.elements.content.find('.description_textarea').replaceWith(viewableText);
                api.elements.content.find('input[type=button]').remove();
                api.elements.content.find('.ajaxprocess').remove();
                api.elements.content.find('.entry_action_description').show();
              }
              api.elements.target.closest('.entry').removeClass('hasfocus').removeClass('popupopen');
            }
          },
          style: {
            classes: 'UseyourDrive description qtip-light'
          }
        });
      });

      /* Load more images */
      var loadmoreimages = function () {
        // the element should probably be expected to be off-screen (beneath the visible viewport) when domready fires, but this can be tested using similar logic
        var element = $(".UseyourDrive[data-token='" + listtoken + "'] .image-container.entry:not(.hidden):last()");
        // is the element at least 10% visible along both axes?
        var visible = element.isOnScreen(0.1, 0.1);
        if (visible) {
          var loadimages = $(".UseyourDrive[data-token='" + listtoken + "']").attr('data-loadimages'),
                  images = $(".UseyourDrive[data-token='" + listtoken + "'] .image-container:hidden:lt(" + loadimages + ")");

          if (images.length > 0) {
            images.each(function () {
              $(this).fadeIn(500);
              $(this).removeClass('hidden');
              $(this).find('img').removeClass('hidden');
            });

            $(".UseyourDrive[data-token='" + listtoken + "'] img.preloading").not('.hidden').unveil(200, null, function () {
              $(this).load(function () {
                $(this).removeClass('preloading');
              });
            });
          } else {
            // tidy up
            $(window).off('scroll', debounced);
          }

        }
      };
      /* wrap it in the functor so that it's only called every 50 ms */
      var debounced = $.noop;
      debounced = loadmoreimages.debounce(50);
      $(window).on('scroll', debounced);
      $(window).trigger('scroll');

      /* Drag and Drop folders and files */
      if ($('#UseyourDrive .entry.moveable').length > 0) {
        $('#UseyourDrive .entry.moveable').not('.parentfolder').draggable({
          revert: "invalid",
          stack: "#UseyourDrive .entry",
          cursor: 'move',
          containment: 'parent',
          distance: 50,
          delay: 50,
          start: function (event, ui) {
            $(this).addClass('isdragged');
            $(this).css('transform', 'scale(0.5)');
          },
          stop: function (event, ui) {
            setTimeout(function () {
              $(this).removeClass('isdragged');
            }, 300);
            $(this).css('transform', 'scale(1)');
          }
        });

        $('#UseyourDrive .entry.folder').droppable({
          accept: $('#UseyourDrive .entry'),
          activeClass: "ui-state-hover",
          hoverClass: "ui-state-active",
          drop: function (event, ui) {
            var listtoken = ui.draggable.closest('.UseyourDrive').attr('data-token');
            $(ui.draggable).fadeOut(500);

            var data = {
              action: 'useyourdrive-move-entry',
              id: ui.draggable.attr('data-id'),
              copy: false,
              target: $(this).attr('data-id'),
              listtoken: listtoken,
              _ajax_nonce: UseyourDrive_vars.move_nonce, //UseyourDrive_vars.move_nonce
            };
            changeDriveEntry(data);
          }
        });
      }

      $(".UseyourDrive[data-token='" + listtoken + "'] .folder, .UseyourDrive[data-token='" + listtoken + "'] .image-folder").unbind('click');
      $(".UseyourDrive[data-token='" + listtoken + "'] .folder, .UseyourDrive[data-token='" + listtoken + "'] .image-folder").click(function (e) {

        if ($(this).hasClass('isdragged')) {
          return false;
        }

        $(".UseyourDrive[data-qtip-id='search-" + listtoken + "'] .search-input").val('');
        var data = {
          listtoken: listtoken,
          id: $(this).attr('data-id')
        };
        getDriveFileList(data);
        e.stopPropagation();
      });

      /* Use timeout to load images in viewport correctly */
      setTimeout(function () {

        $(".UseyourDrive[data-token='" + listtoken + "'] img.preloading").one('error', function () {
          this.src = $(this).attr('data-src-backup');
        });

        $(".UseyourDrive[data-token='" + listtoken + "'] img.preloading").not('.hidden').unveil(200, null, function () {
          $(this).load(function () {
            $(this).removeClass('preloading');
          });
        });

        $(".UseyourDrive[data-token='" + listtoken + "'] img.preloading").not('.hidden').unveil(200, $(".UseyourDrive[data-token='" + listtoken + "'] .ajax-filelist"), function () {
          $(this).load(function () {
            $(this).removeClass('preloading');
          });
        });

        setTimeout(function () {
          updateDriveCollage(listtoken);
        }, 200);
      }, 500);

      $(".UseyourDrive[data-token='" + listtoken + "'] .image-container .image-rollover").css("opacity", "0");
      $(".UseyourDrive[data-token='" + listtoken + "'] .image-container").hover(
              function () {
                $(this).find('.image-rollover, .image-folder-img').stop().animate({opacity: 1}, 400);
              },
              function () {
                $(this).find('.image-rollover, .image-folder-img').stop().animate({opacity: 0}, 400);
              });

      var groupsArr = [];

      if (GD_iLightbox !== false) {
        GD_iLightbox.destroy();
      }

      $('.UseyourDrive[data-token="' + listtoken + '"] .ilightbox-group[rel^="ilightbox["]').each(function () {
        var group = this.getAttribute("rel");
        $.inArray(group, groupsArr) === -1 && groupsArr.push(group);
      });
      $.each(groupsArr, function (i, groupName) {
        var selector = $('.UseyourDrive[data-token="' + listtoken + '"]');

        GD_iLightbox = $('.UseyourDrive[data-token="' + listtoken + '"] .ilightbox-group[rel="' + groupName + '"]').iLightBox({
          skin: UseyourDrive_vars.lightbox_skin,
          path: UseyourDrive_vars.lightbox_path,
          maxScale: 1,
          slideshow: {
            pauseOnHover: true,
            pauseTime: selector.attr('data-pausetime'),
            startPaused: ((selector.attr('data-list') === 'gallery') && (selector.attr('data-slideshow') === '1')) ? false : true
          },
          controls: {
            slideshow: (selector.attr('data-list') === 'gallery') ? true : false,
            arrows: (selector.attr('data-list') === 'gallery') ? false : true,
          },
          keepAspectRatio: true,
          callback: {
            onBeforeLoad: function (api, position) {
              $('.ilightbox-holder').addClass('UseyourDrive');
              $('.ilightbox-holder').find('iframe').addClass('uyd-embedded');
              iframeFix();
            },
            onShow: function (api) {
              if (api.currentElement.find('.empty_iframe').length === 0) {
                api.currentElement.find('.uyd-embedded').after(UseyourDrive_vars.str_iframe_loggedin);
              }

              /* Bugfix for PDF files that open very narrow */
              if (api.currentElement.find('iframe').length > 0) {
                setTimeout(function () {
                  api.currentElement.find('.uyd-embedded').width(api.currentElement.find('.ilightbox-container').width() - 1);
                }, 500);
              }

              api.currentElement.find('.empty_iframe').hide();
              if (api.currentElement.find('img').length !== 0) {
                setTimeout(function () {
                  api.currentElement.find('.empty_iframe').fadeIn();
                }, 5000);
              }

              $('.UseyourDrive .ilightbox-container img').on("contextmenu", function (e) {
                return false;
              });
            }
          },
          errors: {
            loadImage: UseyourDrive_vars.str_imgError_title,
            loadContents: UseyourDrive_vars.str_xhrError_title
          },
          text: {
            next: UseyourDrive_vars.str_next_title,
            previous: UseyourDrive_vars.str_previous_title,
            slideShow: UseyourDrive_vars.str_startslideshow
          }
        });
      });

      /* Disable right clicks */
      $('#UseyourDrive .entry').on("contextmenu", function (e) {
        return false;
      });

      $(".UseyourDrive[data-token='" + listtoken + "'] .entry_checkbox").unbind('click');
      $(".UseyourDrive[data-token='" + listtoken + "'] .entry_checkbox").click(function (e) {
        e.stopPropagation();
        return true;
      });

      $(".UseyourDrive[data-token='" + listtoken + "'] .entry_checkbox :checkbox").click(function (e) {
        if ($(this).prop('checked')) {
          $(this).closest('.entry').addClass('isselected');
        } else {
          $(this).closest('.entry').removeClass('isselected');
        }
      });

      $(".UseyourDrive[data-token='" + listtoken + "'] .entry_linkto").unbind('click');
      $(".UseyourDrive[data-token='" + listtoken + "'] .entry_linkto").click(function (e) {

        var folder_text = $(this).parent().attr('data-name'),
                folder_id = $(this).parent().attr('data-id'),
                user_id = $('#UseyourDrive-UserToFolder .thickbox_opener').attr('data-userid');

        $.ajax({type: "POST",
          url: UseyourDrive_vars.ajax_url,
          data: {
            action: 'useyourdrive-linkusertofolder',
            id: folder_id,
            text: folder_text,
            userid: user_id,
            _ajax_nonce: UseyourDrive_vars.createlink_nonce
          },
          beforeSend: function () {
            $(".UseyourDrive[data-token='" + listtoken + "'] .loading").fadeTo(400, 1);
          },
          complete: function () {
            $(".UseyourDrive[data-token='" + listtoken + "'] .loading").fadeOut(400);
            tb_remove();
          },
          success: function (response) {
            if (response === '1') {
              $('#UseyourDrive-UserToFolder .thickbox_opener .uyd-linkedto').html(folder_text);
              $('#UseyourDrive-UserToFolder .thickbox_opener').removeClass("thickbox_opener");
              $('#UseyourDrive-UserToFolder .thickbox_opener .uyd-unlinkbutton').removeClass("disabled");
            }
          },
          dataType: 'text'
        });

        e.stopPropagation();
        return true;
      });

      $(".UseyourDrive[data-token='" + listtoken + "'] .entry_action_view").unbind('click');
      $(".UseyourDrive[data-token='" + listtoken + "'] .entry_action_view").click(function () {
        $('.qtip.UseyourDrive').qtip('hide');
        var dataid = $(this).closest("ul").attr('data-id');
        var link = $(".UseyourDrive[data-token='" + listtoken + "'] .entry[data-id='" + dataid + "']").find(".entry_link")[0].click();
      });

      $(".UseyourDrive[data-token='" + listtoken + "'] .entry_action_export").unbind('click');
      $(".UseyourDrive[data-token='" + listtoken + "'] .entry_action_export").click(function () {
        $('.qtip.UseyourDrive').qtip('hide');
        var dataid = $(this).closest("ul").attr('data-id');
        var link = $(".UseyourDrive[data-token='" + listtoken + "'] .entry[data-id='" + dataid + "']").find(".entry_link").attr('href');
        var dataname = $(".UseyourDrive[data-token='" + listtoken + "'] .entry[data-id='" + dataid + "']").attr('data-name');
        link += '&extension=' + $(this).attr('data-key');
        sendDriveGooglePageView('Export', dataname + ' > ' + $(this).attr('data-key'));
        window.location = link;
      });

      $(".UseyourDrive[data-token='" + listtoken + "'] .entry_action_download").unbind('click');
      $(".UseyourDrive[data-token='" + listtoken + "'] .entry_action_download").click(function (e) {
        e.stopPropagation();

        var href = $(this).attr('href'),
                dataname = $(this).attr('data-filename');

        sendDriveGooglePageView('Download', dataname);

        // Delay a few milliseconds for Tracking event
        setTimeout(function () {
          window.location = href;
        }, 300);

        return false;

      });

      $(".UseyourDrive[data-token='" + listtoken + "'] .entry_action_shortlink").unbind('click');
      $(".UseyourDrive[data-token='" + listtoken + "'] .entry_action_shortlink").click(function () {
        $('.qtip.UseyourDrive').qtip('hide');

        var dataid = $(this).closest("ul").attr('data-id');

        if (dataid.length === 0) {
          var dataid = $(".UseyourDrive[data-token='" + listtoken + "']").attr('data-id');
        }

        var dialog_html = $("<div class='dialog' title='" + UseyourDrive_vars.str_share_link + "'><input type='text' class='shared-link-url' value='" + UseyourDrive_vars.str_create_shared_link + "' style='width: 98%;' readonly/></div>");
        var l18nButtons = {};
        l18nButtons[UseyourDrive_vars.str_close_title] = function () {
          $(this).dialog("destroy");
        };
        dialog_html.dialog({
          dialogClass: 'UseyourDrive',
          resizable: false,
          height: 150,
          width: 400, modal: true,
          buttons: l18nButtons,
          open: function (event, ui) {

            $.ajax({type: "POST",
              url: UseyourDrive_vars.ajax_url,
              data: {
                action: 'useyourdrive-create-link',
                listtoken: listtoken,
                id: dataid,
                _ajax_nonce: UseyourDrive_vars.createlink_nonce
              },
              beforeSend: function () {
                $(".UseyourDrive[data-token='" + listtoken + "'] .loading").fadeTo(400, 1);
              },
              complete: function () {
                $(".UseyourDrive[data-token='" + listtoken + "'] .loading").fadeOut(400);
              },
              success: function (response) {
                if (response !== null) {
                  if (response.link !== null) {
                    $(dialog_html).find('.shared-link-url').val(response.link);
                    sendDriveGooglePageView('Create shared link');
                  } else {
                    $(dialog_html).find('.shared-link-url').val(response.error);
                  }
                }
              },
              dataType: 'json'
            });
          }
        });
        return false;
      });
      $(".UseyourDrive[data-token='" + listtoken + "'] .entry_action_delete").unbind('click');
      $(".UseyourDrive[data-token='" + listtoken + "'] .entry_action_delete").click(function () {
        $('.qtip.UseyourDrive').qtip('hide');

        var dataname = $(this).closest("ul").attr('data-name');
        var dataid = $(this).closest("ul").attr('data-id');
        var dialog_html = $("<div class='dialog' title='" + UseyourDrive_vars.str_delete_title + "'><p>" + UseyourDrive_vars.str_delete + ' <em>' + dataname + "</em></p></div>");
        var l18nButtons = {};
        l18nButtons[UseyourDrive_vars.str_delete_title] = function () {
          var data = {
            action: 'useyourdrive-delete-entry',
            id: dataid,
            listtoken: listtoken,
            _ajax_nonce: UseyourDrive_vars.delete_nonce
          };
          changeDriveEntry(data);
          $(this).dialog("destroy");
        };
        l18nButtons[UseyourDrive_vars.str_cancel_title] = function () {
          $(this).dialog("destroy");
        };
        dialog_html.dialog({
          dialogClass: 'UseyourDrive',
          resizable: false,
          height: 200,
          width: 400,
          modal: true,
          buttons: l18nButtons
        });
        return false;
      });

      $(".UseyourDrive[data-token='" + listtoken + "'] .entry_action_rename").unbind('click');
      $(".UseyourDrive[data-token='" + listtoken + "'] .entry_action_rename").click(function () {
        $('.qtip.UseyourDrive').qtip('hide');

        var dataname = $(this).closest("ul").attr('data-name');
        var dataid = $(this).closest("ul").attr('data-id');
        var dialog_html = $("<div class='dialog' title='" + UseyourDrive_vars.str_rename_title + "'><p>" + UseyourDrive_vars.str_rename +
                '<input type="text" name="newname" id="newname" value="' + dataname + '" class="text ui-widget-content ui-corner-all" style=" width: 98%; "/></p></div>');
        var l18nButtons = {};
        l18nButtons[UseyourDrive_vars.str_rename_title] = function () {
          var data = {
            action: 'useyourdrive-rename-entry',
            id: dataid,
            newname: encodeURIComponent($('#newname').val()),
            listtoken: listtoken,
            _ajax_nonce: UseyourDrive_vars.rename_nonce
          };
          changeDriveEntry(data);
          $(this).dialog("destroy");
        };
        l18nButtons[UseyourDrive_vars.str_cancel_title] = function () {
          $(this).dialog("destroy");
        };
        dialog_html.dialog({
          dialogClass: 'UseyourDrive',
          resizable: false,
          height: 200,
          width: 400,
          modal: true,
          buttons: l18nButtons
        });
        return false;
      });
      $(".UseyourDrive[data-token='" + listtoken + "'] .entry_action_description").unbind('click');
      $(".UseyourDrive[data-token='" + listtoken + "'] .entry_action_description").click(function () {
        var button = $(this);
        var dataid = $(this).attr("data-id");
        var qtipid = $(this).closest(".UseyourDrive").attr('data-qtip-id');
        var listtoken = $('a[data-hasqtip="' + qtipid + '"]').closest('.UseyourDrive').attr('data-token');

        var descriptiondiv = $(this).closest(".UseyourDrive").find('.description_text');
        var currentText = descriptiondiv.html();
        var editableText = $("<textarea />").addClass('description_textarea');
        editableText.val(currentText.replace(/<br\s?\/?>/g, "\n"));
        descriptiondiv.replaceWith(editableText);
        var loading = $('<img src="' + UseyourDrive_vars.plugin_url + '/css/clouds/cloud_loading_64.gif" width="32" height="32" />').addClass('ajaxprocess').hide();
        var savebutton = $('<input type="button" value="' + UseyourDrive_vars.str_save_title + '"/>');
        editableText.after(loading).after(savebutton);
        editableText.focus();

        button.hide();
        savebutton.click(function () {
          var newdescription = editableText.val();
          var viewableText = $("<div>").addClass('description_text');


          $.ajax({type: "POST",
            url: UseyourDrive_vars.ajax_url,
            data: {
              action: 'useyourdrive-edit-description-entry',
              id: dataid,
              newdescription: encodeURIComponent(newdescription),
              listtoken: listtoken,
              _ajax_nonce: UseyourDrive_vars.description_nonce
            },
            beforeSend: function () {
              savebutton.prop("disabled", true).fadeTo(400, 0.3);
              loading.show();
            },
            complete: function () {
              button.show();
              savebutton.remove();
              loading.remove();
            },
            error: function () {
              viewableText.html(currentText);
              editableText.replaceWith(viewableText);
            },
            success: function (response) {
              if (response !== null) {
                if (typeof response.description !== 'undefined') {
                  newdescription = response.description;
                  viewableText.html(newdescription.replace(/\r\n|\r|\n/g, "<br />"));
                  editableText.replaceWith(viewableText);
                  return;
                }
              }
              viewableText.html(currentText);
              editableText.replaceWith(viewableText);
            },
            dataType: 'json'
          });
        });

        return false;
      });

      $(".UseyourDrive[data-token='" + listtoken + "'] .newfolder").unbind('click');
      $(".UseyourDrive[data-token='" + listtoken + "'] .newfolder").click(function () {
        $('.qtip.UseyourDrive').qtip('hide');
        var lastFolder = $(".UseyourDrive[data-token='" + listtoken + "']").attr('data-id');
        var dialog_html = $("<div class='dialog' title='" + UseyourDrive_vars.str_addfolder_title + "'><p>" +
                '<input type="text" name="newfolder" id="newfolder" value="' + UseyourDrive_vars.str_addfolder + '" class="text ui-widget-content ui-corner-all" style=" width: 90%; "/></p></div>');
        var l18nButtons = {};
        l18nButtons[UseyourDrive_vars.str_addfolder_title] = function () {
          var data = {
            action: 'useyourdrive-add-folder',
            newfolder: encodeURIComponent($('#newfolder').val()),
            lastFolder: lastFolder,
            listtoken: listtoken,
            _ajax_nonce: UseyourDrive_vars.addfolder_nonce
          };
          changeDriveEntry(data);
          $(this).dialog("destroy");
        };
        l18nButtons[UseyourDrive_vars.str_cancel_title] = function () {
          $(this).dialog("destroy");
        };
        dialog_html.dialog({
          dialogClass: 'UseyourDrive', resizable: false,
          height: 200,
          width: 400,
          modal: true,
          buttons: l18nButtons
        });
        return false;
      });
    }


    /* Remove Folder upload button if isn't supported by browser */
    if (!is_chrome) {
      $('.upload-multiple-files').parent().remove();
    }

    // Initialize the jQuery File Upload widget:
    $('.UseyourDrive .fileuploadform').each(function () {
      $(this).fileupload({
        url: UseyourDrive_vars.ajax_url,
        type: 'POST',
        dataType: 'json',
        autoUpload: true,
        maxFileSize: UseyourDrive_vars.post_max_size,
        acceptFileTypes: new RegExp($(this).find('input[name="acceptfiletypes"]').val(), "i"),
        dropZone: $(this).closest('.UseyourDrive'),
        messages: {
          maxNumberOfFiles: UseyourDrive_vars.maxNumberOfFiles,
          acceptFileTypes: UseyourDrive_vars.acceptFileTypes,
          maxFileSize: UseyourDrive_vars.maxFileSize,
          minFileSize: UseyourDrive_vars.minFileSize
        },
        limitConcurrentUploads: 3,
        disableImageLoad: true,
        disableImageResize: true,
        disableImagePreview: true,
        disableAudioPreview: true,
        disableVideoPreview: true,
        uploadTemplateId: null,
        downloadTemplateId: null,
        add: function (e, data) {
          var listtoken = $(this).attr('data-token');

          $.each(data.files, function (index, file) {
            file.hash = file.name.hashCode() + '_' + Math.floor(Math.random() * 1000000);
            file.listtoken = listtoken;
            file = validateFile(file);
            var row = renderFileUploadRow(file);

            if (file.error !== false) {
              data.files.splice(index, 1);
            }
          });

          if (data.autoUpload || (data.autoUpload !== false &&
                  $(this).fileupload('option', 'autoUpload'))) {
            if (data.files.length > 0) {
              data.process().done(function () {
                data.submit();
              });
            }
          }
        }
      }).on('fileuploadsubmit', function (e, data) {
        var datatoken = $(this).attr('data-token');
        $(".UseyourDrive[data-token='" + datatoken + "'] .loading").addClass('upload');
        $(".UseyourDrive[data-token='" + datatoken + "'] .loading").fadeTo(400, 1);

        var filehash;
        $.each(data.files, function (index, file) {
          uploadStart(file);
          filehash = file.hash;
        });

        $('.gform_button:submit').prop("disabled", false).fadeTo(400, 0.3);

        data.formData = {
          action: 'useyourdrive-upload-file',
          type: 'do-upload',
          hash: filehash,
          lastFolder: $(".UseyourDrive[data-token='" + datatoken + "']").attr('data-id'),
          listtoken: datatoken,
          _ajax_nonce: UseyourDrive_vars.upload_nonce
        };

      }).on('fileuploadprogress', function (e, data) {
        var progress = parseInt(data.loaded / data.total * 100, 10) / 2;

        $.each(data.files, function (index, file) {
          uploadProgress(file, {percentage: progress, progress: 'uploading_to_server'});

          if (progress >= 50) {
            uploadProgress(file, {percentage: 50, progress: 'uploading_to_cloud'});

            setTimeout(function () {
              getProgress(file);
            }, 2000);
          }
        });

      }).on('fileuploadstopped', function () {
        $('.gform_button:submit').prop("disabled", false).fadeTo(400, 1);
      }).on('fileuploaddone', function (e, data) {
        sendDriveGooglePageView('Upload file');
      }).on('fileuploadalways', function (e, data) {

        if (data.result !== null) {
          if (typeof data.result.status !== 'undefined') {
            if (data.result.status.progress === 'finished' || data.result.status.progress === 'failed') {
              uploadFinished(data.result.file);
            }
          } else {
            data.result.file.error = UseyourDrive_vars.str_error;
            uploadFinished(data.result.file);
          }
        } else {
          $.each(data.files, function (index, file) {
            file.error = UseyourDrive_vars.str_error;
            uploadFinished(file);
          });
        }
      }).on('fileuploaddrop', function (e, data) {
        var uploadcontainer = $(this);
        $('html, body').animate({
          scrollTop: uploadcontainer.offset().top
        }, 1500);
      });
    });

    /* ***** Helper functions for File Upload ***** */
    /* Validate File for Upload */
    function validateFile(file) {

      var maxFileSize = $(".UseyourDrive[data-token='" + file.listtoken + "']").find('input[name="maxfilesize"]').val(),
              acceptFileType = new RegExp($(".UseyourDrive[data-token='" + file.listtoken + "']").find('input[name="acceptfiletypes"]').val(), "i");

      file.error = false;
      if (file.name.length && !acceptFileType.test(file.name)) {
        file.error = UseyourDrive_vars.acceptFileTypes;
      }
      if (maxFileSize !== '' && file.size > 0 && file.size > maxFileSize) {
        file.error = UseyourDrive_vars.maxFileSize;
      }
      return file;
    }

    /* Get Progress for uploading files to cloud*/
    function getProgress(file) {

      $.ajax({type: "POST",
        url: UseyourDrive_vars.ajax_url,
        data: {
          action: 'useyourdrive-upload-file',
          type: 'get-status',
          listtoken: file.listtoken,
          hash: file.hash,
          _ajax_nonce: UseyourDrive_vars.upload_nonce
        },
        success: function (response) {
          if (response !== null) {
            if (typeof response.status !== 'undefined') {
              if (response.status.progress === 'starting' || response.status.progress === 'uploading') {
                setTimeout(function () {
                  getProgress(response.file);
                }, 1500);
              }
              uploadProgress(response.file, {percentage: 50 + (response.status.percentage / 2), progress: response.status.progress});
            } else {
              file.error = UseyourDrive_vars.str_error;
              uploadFinished(file);
            }
          }
        },
        error: function (response) {
          file.error = UseyourDrive_vars.str_error;
          uploadFinished(file);
        },
        complete: function (response) {

        },
        dataType: 'json'
      });
    }

    /* Render file in upload list */
    function renderFileUploadRow(file) {
      var row = ($(".UseyourDrive[data-token='" + file.listtoken + "']").find('.template-row').clone().removeClass('template-row'));

      row.attr('data-file', file.name).attr('data-id', file.hash);
      row.find('.file-name').text(file.name);
      if (file.size !== 'undefined' && file.size > 0) {
        row.find('.file-size').text(humanFileSize(file.size, true));
      }
      row.find('.upload-thumbnail img').attr('src', getThumbnail(file));

      row.addClass('template-upload');
      row.find('.upload-status').removeClass().addClass('upload-status queue').text(UseyourDrive_vars.str_inqueue);
      row.find('.upload-status-icon').removeClass().addClass('upload-status-icon fa fa-circle');

      $(".UseyourDrive[data-token='" + file.listtoken + "'] .fileupload-list .files").append(row);

      $('.UseyourDrive .fileuploadform[data-token="' + file.listtoken + '"] div.fileupload-drag-drop').fadeOut();

      if (typeof file.error !== 'undefined' && file.error !== false) {
        uploadFinished(file);
      }

      return row;
    }

    function uploadStart(file) {
      var row = $(".UseyourDrive[data-token='" + file.listtoken + "'] .fileupload-list [data-id='" + file.hash + "']");
      row.find('.upload-status').removeClass().addClass('upload-status succes').text(UseyourDrive_vars.str_uploading_local);
      row.find('.upload-status-icon').removeClass().addClass('upload-status-icon fa fa-circle-o-notch fa-spin');
      row.find('.upload-progress').slideDown();
      $('input[type="submit"]').prop('disabled', true)
    }

    /* Render the progress of uploading cloud files */
    function uploadProgress(file, status) {
      var row = $(".UseyourDrive[data-token='" + file.listtoken + "'] .fileupload-list [data-id='" + file.hash + "']");

      row.find('.progress')
              .attr('aria-valuenow', status.percentage)
              .children().first().fadeIn()
              .animate({
                width: status.percentage + '%'
              }, 'slow', function () {
                if (status.progress === 'uploading_to_cloud') {
                  row.find('.upload-status').text(UseyourDrive_vars.str_uploading_cloud);
                }
              });

      if (status.progress === 'finished' || status.progress === 'failed') {
        uploadFinished(file);
      }
    }

    function uploadFinished(file) {
      var row = $(".UseyourDrive[data-token='" + file.listtoken + "'] .fileupload-list [data-id='" + file.hash + "']");

      row.addClass('template-download').removeClass('template-upload');
      row.find('.file-name').text(file.name);
      row.find('.upload-thumbnail img').attr('src', getThumbnail(file));
      row.find('.upload-progress').slideUp();

      if (typeof file.error !== 'undefined' && file.error !== false) {
        row.find('.upload-status').removeClass().addClass('upload-status error').text(UseyourDrive_vars.str_error);
        row.find('.upload-status-icon').removeClass().addClass('upload-status-icon fa fa-exclamation-circle');
        row.find('.upload-error').text(file.error).slideUp().delay(500).slideDown();
      } else {
        row.find('.upload-status').removeClass().addClass('upload-status succes').text(UseyourDrive_vars.str_success);
        row.find('.upload-status-icon').removeClass().addClass('upload-status-icon fa fa-check-circle');

        if (typeof _Driveuploads[file.listtoken] === 'undefined') {
          _Driveuploads[file.listtoken] = {};
        }

        _Driveuploads[file.listtoken][file.fileid] = {
          "name": file.name,
          "path": file.completepath,
          "size": file.filesize,
          "link": file.link
        };

        $('.UseyourDrive .fileuploadform[data-token="' + file.listtoken + '"] .fileupload-filelist').val(JSON.stringify(_Driveuploads[file.listtoken]));
      }

      row.delay(5000).animate({"opacity": "0"}, "slow", function () {
        if ($(this).parent().find('.template-upload').length <= 1) {
          $(this).closest('.fileuploadform').find('div.fileupload-drag-drop').fadeIn();

          /* Update Filelist */
          clearTimeout(_updateDrivetimer);
          var formData = {
            listtoken: file.listtoken
          };

          _GDcache = [];
          getDriveFileList(formData, 'hardrefresh');

        }

        $(this).remove();
      });

      $('input[type="submit"]').prop('disabled', false)
    }

    /* Get thumbnail for local and cloud files */
    function getThumbnail(file) {

      var thumbnailUrl = UseyourDrive_vars.plugin_url + '/css/icons/';
      if (typeof file.thumbnail === 'undefined' || file.thumbnail === null || file.thumbnail === '') {
        var icon;

        if (typeof file.type === 'undefined' || file.type === null) {
          icon = 'icon_11_generic_xl128';
        } else if (file.type.indexOf("word") >= 0) {
          icon = 'icon_11_word_xl128';
        } else if (file.type.indexOf("excel") >= 0 || file.type.indexOf("spreadsheet") >= 0) {
          icon = 'icon_11_excel_xl128';
        } else if (file.type.indexOf("powerpoint") >= 0 || file.type.indexOf("presentation") >= 0) {
          icon = 'icon_11_powerpoint_xl128';
        } else if (file.type.indexOf("image") >= 0) {
          icon = 'icon_11_image_xl128';
        } else if (file.type.indexOf("audio") >= 0) {
          icon = 'icon_11_audio_xl128';
        } else if (file.type.indexOf("video") >= 0) {
          icon = 'icon_11_video_xl128';
        } else if (file.type.indexOf("pdf") >= 0) {
          icon = 'icon_11_pdf_xl128';
        } else if (file.type.indexOf("text") >= 0) {
          icon = 'icon_11_text_xl128';
        } else {
          icon = 'icon_11_generic_xl128';
        }
        return thumbnailUrl + icon + '.png';
      } else {
        return file.thumbnail;
      }

    }

    /* drag and drop functionality*/
    $(document).bind('dragover', function (e) {
      var dropZone = $('.UseyourDrive .fileuploadform').closest('.UseyourDrive'),
              timeout = window.dropZoneTimeout;
      if (!timeout) {
        dropZone.addClass('in');
      } else {
        clearTimeout(timeout);
      }
      var found = false,
              node = e.target;
      do {
        if ($(node).is(dropZone)) {
          found = true;
          break;
        }
        node = node.parentNode;
      } while (node !== null);
      if (found) {
        $(node).addClass('hover');
      } else {
        dropZone.removeClass('hover');
      }
      window.dropZoneTimeout = setTimeout(function () {
        window.dropZoneTimeout = null;
        dropZone.removeClass('in hover');
      }, 100);
    });
    $(document).bind('drop dragover', function (e) {
      e.preventDefault();
    });

    // Resize handlers
    windowwidth = $(window).width();
    $(window).resize(function () {

      if (windowwidth === $(window).width()) {
        windowwidth = $(window).width();
        return;
      }
      windowwidth = $(window).width();


      $('.UseyourDrive.media.video .jp-jplayer').each(function () {

        var status = ($(this).data().jPlayer.status);
        if (status.videoHeight !== 0 && status.videoWidth !== 0) {
          var ratio = status.videoWidth / status.videoHeight;
          var jpvideo = $(this);
          if ($(this).find('object').length > 0) {
            var jpobject = $(this).find('object');
          } else {
            var jpobject = $(this).find('video');
          }

          if (jpvideo.height() !== jpvideo.width() / ratio) {
            if ((screen.height >= (jpvideo.width() / ratio)) || (status.cssClass !== "jp-video-full")) {
              jpobject.height(jpobject.width() / ratio);
              jpvideo.height(jpobject.width() / ratio);
            } else {
              jpobject.width(screen.height * ratio);
              jpvideo.width(screen.height * ratio);
            }
          }
          $(this).parent().find(".jp-video-play").height(jpvideo.height());
        }

      });     // set a timer to re-apply the plugin
      if (_resizeDriveTimer) {
        clearTimeout(_resizeDriveTimer);
      }

      $(".UseyourDrive.gridgallery .image-collage").fadeTo(100, 0);
      $(".UseyourDrive.uyd-grid .layout-grid").fadeTo(100, 0);

      _resizeDriveTimer = setTimeout(function () {
        $(".UseyourDrive.gridgallery .image-collage").each(function () {
          var listtoken = $(this).closest('.UseyourDrive').attr('data-token');
          updateDriveCollage(listtoken);
        });

        $(".UseyourDrive .layout-grid").each(function () {
          var listtoken = $(this).closest('.UseyourDrive').attr('data-token');
          updateLayoutFilelist(listtoken);
        });
      }, 500);
    });

    var downloadDriveURL = function downloadDriveURL(url) {
      var hiddenIFrameID = 'hiddenDownloader',
              iframe = document.getElementById(hiddenIFrameID);
      if (iframe === null) {
        iframe = document.createElement('iframe');
        iframe.id = hiddenIFrameID;
        iframe.style.display = 'none';
        document.body.appendChild(iframe);
      }
      iframe.src = url;
    };
    readGDriveArrCheckBoxes = function (element) {
      var values = $(element + ":checked").map(function () {
        return this.value;
      }).get();
      return values;
    };

    iframeFix();

  });

  function iframeFix() {
    /* Safari bug fix for embedded iframes*/
    if (/iPhone|iPod|iPad/.test(navigator.userAgent)) {
      $('iframe.uyd-embedded').each(function () {
        if ($(this).closest('#safari_fix').length === 0) {
          $(this).wrap(function () {
            return $('<div id="safari_fix"/>').css({
              'width': "100%",
              'height': "100%",
              'overflow': 'auto',
              'z-index': '2',
              '-webkit-overflow-scrolling': 'touch'
            });
          });
        }
      });
    }
  }

  $.fn.isOnScreen = function (x, y) {

    if (x == null || typeof x == 'undefined')
      x = 1;
    if (y == null || typeof y == 'undefined')
      y = 1;

    var win = $(window);

    var viewport = {
      top: win.scrollTop(),
      left: win.scrollLeft()
    };
    viewport.right = viewport.left + win.width();
    viewport.bottom = viewport.top + win.height();

    var height = this.outerHeight();
    var width = this.outerWidth();

    if (!width || !height) {
      return false;
    }

    var bounds = this.offset();
    bounds.right = bounds.left + width;
    bounds.bottom = bounds.top + height;

    var visible = (!(viewport.right < bounds.left || viewport.left > bounds.right || viewport.bottom < bounds.top || viewport.top > bounds.bottom));

    if (!visible) {
      return false;
    }

    var deltas = {
      top: Math.min(1, (bounds.bottom - viewport.top) / height),
      bottom: Math.min(1, (viewport.bottom - bounds.top) / height),
      left: Math.min(1, (bounds.right - viewport.left) / width),
      right: Math.min(1, (viewport.right - bounds.left) / width)
    };

    return (deltas.left * deltas.right) >= x && (deltas.top * deltas.bottom) >= y;

  };

});

function sendDriveGooglePageView(action, value) {
  if (UseyourDrive_vars.google_analytics === "1") {
    if (typeof ga !== "undefined" && ga !== null) {
      ga('send', 'event', 'Use-your-Drive', action, value);
    } else if (typeof _gaq !== "undefined" && _gaq !== null) {
      _gaq.push(['_trackEvent', 'Use-your-Drive', action, value]);
    }
  }
}

/* Helper functions */
function humanFileSize(bytes, si) {
  var thresh = si ? 1000 : 1024;
  if (Math.abs(bytes) < thresh) {
    return bytes + ' B';
  }
  var units = si
          ? ['kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB']
          : ['KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB'];
  var u = -1;
  do {
    bytes /= thresh;
    ++u;
  } while (Math.abs(bytes) >= thresh && u < units.length - 1);
  return bytes.toFixed(1) + ' ' + units[u];
}

String.prototype.hashCode = function () {
  var hash = 0, i, char;
  if (this.length === 0)
    return hash;
  for (i = 0, l = this.length; i < l; i++) {
    char = this.charCodeAt(i);
    hash = ((hash << 5) - hash) + char;
    hash |= 0; // Convert to 32bit integer
  }
  return Math.abs(hash);
};

Function.prototype.debounce = function (threshold) {
  var callback = this;
  var timeout;
  return function () {
    var context = this, params = arguments;
    window.clearTimeout(timeout);
    timeout = window.setTimeout(function () {
      callback.apply(context, params);
    }, threshold);
  };
};