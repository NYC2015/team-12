function toolPanel($container, is_open) {
  var dropshadow = 6;
  var $handle = $container.find('.handle');
  var panel = $container.attr('id');

  if (is_open) {
    $container.animate({
      'left': '-' + ($container.width() - $handle.outerWidth() + dropshadow) + 'px',
    }, 500, function() {
      if ($container.is(':hidden'))
        $container.fadeIn(function() {
          if (panel == 'filtersContainer') {
            $(this).trigger('sizeLabels');
            $container.css('left', '-' + ($container.width() - $handle.outerWidth() + dropshadow) + 'px');
          }
        });
    });
  } else {
    $container.animate({
      'left': '0px',
    }, 500);
  }
}

function sizeToolContainer(panel) {
  var containerWidth = 108;

  $('#' + panel + 'Container .panelContainer .panel > label, #' + panel + 'Container .panelContainer .panel > .label').each(function() {
    var mywidth = $(this).outerWidth();

    if (mywidth > containerWidth)
      containerWidth = mywidth;
  });

  /* For the scroll bar */
  if ($('#' + panel + 'Container .panelContainer .panel').height() > $('#' + panel + 'Container .panelContainer').height()) {
    containerWidth += 20;
  }

  $('#' + panel + 'Container .panelContainer').width(containerWidth);

  toolPanel($('#' + panel + 'Container'), true);
}

function populateFilters() {
  /* Clear panel first */
  $('#filtersContainer .panel').empty();    

  /* How many headers are there?*/
  var headerrows = $('table.tablesorter .tablesorter-headerRow').length - 1;

  /* Assume last row has all the cells */
  var $headercols = $('table.tablesorter .tablesorter-headerRow:eq(' + headerrows + ') th:not(.filter-false)');

  var prefix = [];
  var cloneid = 0;
  $.each($headercols, function() {
    var col = $(this).attr('data-column');

    if (headerrows > 0) {
      $.each($('table.tablesorter .tablesorter-headerRow'), function(index) {
        if (index < headerrows) {
          if ($(this).find('th[data-column=' + col + ']').length > 0) {
            prefix[index] = $(this).find('th[data-column=' + col + ']').text();
          }
        }
      });
    }

    var indx = $(this).index();

    $.each($('table.tablesorter .tablesorter-filter-row td:eq(' + indx + ')').find('input, select'), function() {
      $(this).attr('data-cloneid', cloneid);
      cloneid++;
    });

    var label = prefix.join(' ') + $(this).text();
    var $filter = $('table.tablesorter .tablesorter-filter-row td:eq(' + indx + ')').html();

    $('<div class="label"><div class="colname">' + label + '</div><div class="colinput">' + $filter + '</div></div>').appendTo('#filtersContainer .panel');
  });

  /* Remove hidden inputs, just causes grief */
  $('#filtersContainer .panel .label .colinput input[type=hidden]').remove();

  /* bind to original input */
  $.each($('#filtersContainer .panel').find('input, select'), function() {
    var $input = $(this);
    var inputid = $input.attr('data-cloneid');
    var $tsfilter = $('table.tablesorter .tablesorter-filter-row').find($input.prop('tagName') + '[data-cloneid=' + inputid + ']');

    /* readd datepicker if it was there */
    if ($input.hasClass('hasDatepicker')) {
      $input.attr('id', '')
      .removeClass('hasDatepicker')
      .removeData('datepicker')
      .off()
      .datepicker();
    }

    $input.val($tsfilter.val());

    $input.on('change', function() {
      /* Things are a little more complex for datePicker */
      if ($input.hasClass('hasDatepicker')) {
        $tsfilter.datepicker('setDate', $(this).val());
        var dp_inst = $.datepicker._getInst($tsfilter[0]);
        var dp_onClose = $.datepicker._get(dp_inst, 'onClose');
        if (dp_onClose) {
          dp_onClose.apply((dp_inst.input ? dp_inst.input[0] : null), [(dp_inst.input ? dp_inst.input.val() : ''), dp_inst]);
        }
      } else {
        $tsfilter.val($(this).val());
        $tsfilter.trigger('change');
      }
    });
  });

  if ($('#filtersContainer').is(':visible'))
    $('#filtersContainer').trigger('sizeLabels');
  
  $('#filtersContainer .handle').toggleClass('inuse', $('table.tablesorter')[0].config.lastSearch.join('') !== '');
}

function resizeLabels() {
  var labelwidth = 50;

  $.each($('#filtersContainer .panel .tablesorter-filter'), function() {
    var mylabelwidth = $(this).closest('label, .label').find('.colname').outerWidth();

    if (mylabelwidth > labelwidth)
      labelwidth = mylabelwidth;
  });

  $('#filtersContainer .panel .colname').width(labelwidth + 5);
  $('#filtersContainer .panel .colname').css('min-width', labelwidth + 5);

  sizeToolContainer('filters');
}

$(function () {
  if (typeof jQuery !== 'undefined') {
    $(document).ready(function() {
      if (typeof $.datepicker !== 'undefined') {
        $.datepicker.setDefaults({
          dateFormat: 'yy-mm-dd',
          buttonImage: true,
          changeMonth: true,
          changeYear: true,
          showMonthAfterYear: true,
          showAnim: 'fadeIn',
          showOtherMonths: true,
          selectOtherMonths: true,
          showButtonPanel: true
        });
      }
    });
  }


  $('.removeRow').on('click', function() {
    $(this).closest('tr').hide();
  });

  $('#filtersContainer').on('sizeLabels', function() {
    resizeLabels();
  });

  $('#panelTool .toolContainer').on('click', function(event) {
    event.stopPropagation();
  });

  $('#panelTool .toolContainer .handle').on('click', function() {
    var $container = $(this).closest('.toolContainer');

    $(this).tipsy('hide')

    /* close open containers */
    $.each($('#panelTool .toolContainer'), function() {
      if ($(this).css('left') == '0px')
        toolPanel($(this), true);
    });

    var is_open = false;
    if ($container.css('left') == '0px')
      is_open = true;    

    toolPanel($container, is_open);
  });

  // add tipsy tooltip
  $('#panelTool .handle[title]').tipsy({ gravity: 'w', offset: 5 });

  setTimeout(function() {
    sizeToolContainer('columnSelector');
  }, 500);

  setTimeout(function() {
    sizeToolContainer('filters');
  }, 500);

  $('table.tablesorter').on('columnUpdate', function() {
     $('#columnSelectorContainer .handle').toggleClass('inuse', $('#columnSelectorContainer input[type=checkbox]').not('.checked').length > 0);
  });

  $('table.tablesorter').on('filterEnd', function(event, data) {  
    setTimeout(function() {
      populateFilters();
    }, 500);
  });

  $('table.tablesorter').on('pagerComplete', function(event, data) {
    var pc = $('table.tablesorter')[0].config.pager;
    var perc = Math.round((pc.endRow / pc.filteredRows) * 100);
    $('#pager .pager_progress').width(perc + '%');
  });

  $("#table")
  .tablesorter({
    debug: false,
    theme: 'default',
    sortList: [
      [1, 0], [0, 0]
    ],
    headerTemplate: '{content} {icon}<div class="spacer"><div class="separator"></div</div>',
    cssIconAsc: 'fa-sort-asc',
    cssIconDesc: 'fa-sort-desc',
    cssIconNone: 'fa-sort',
    cssIcon: 'fa',
    widgets: ['pager', 'zebra', 'filter', 'cssStickyHeaders', 'columnSelector', 'columns'],
    widgetOptions: {
      columnSelector_container: '#columnSelectorContainer .panel',
      columnSelector_mediaquery: false,
      columnSelector_saveColumns: false,
      columnSelector_layout: '<label><input type="checkbox"><span>{name}</span></label>',
      columnSelector_columns: {
        6: 'disable'
      },
      pager_selectors: {
        container: '#pager'
      },
      pager_output: 'Showing {startRow} to {endRow} of {filteredRows} results',
      pager_size: 50,
      filter_liveSearch: false,
      filter_hideFilters: false,
      filter_placeholder: {
        search: 'Search...',
        from: 'From...',
        to: 'To...',
        select: ''
      },
      filter_formatter: {
        5: function($cell, indx) {
          return $.tablesorter.filterFormatter.uiDatepicker($cell, indx, {
            textFrom: '',
            textTo: '',
            changeMonth: true,
            changeYear: true
          });
        }
      }
    }
  });
});