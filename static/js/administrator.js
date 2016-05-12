(function($){
    /*
     * Add search to controller_administrator_item
     */
    $(document).ready(
        function(){
            var administrator_item_timeout = null;
            
            //$('.controller.administrator_menus .action.new-menu').popover(
            //    {
            //        placement: 'right',
            //        html: true,
            //        //template: '<div class="popover" role="tooltip"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div></div>',
            //        title: 'New menu item',
            //        content: '<div class="ajax-form"><div class="top bottom text-center"><span class="fa fa-circle-o-notch fa-spin fa-3x"></span></div></div>'
            //    }
            //);
            //
            //$('.controller.administrator_menus .action.new-menu').on(
            //    'inserted.bs.popover',
            //    function(event){
            //        alert('event fired');
            //        console.log(event);
            //        $.ajax({
            //            url: '/administrator/menus/menu-item-form',
            //            success: function(data){
            //                $(this).find('.ajax-form').children().replaceWith(data);
            //                //$(this).find('.ajax-form').fadeOut("slow", function(){
            //                //    $(this).find('.ajax-form').children().replaceWith(data);
            //                //    $(this).find('.ajax-form').fadeIn("slow");
            //                //});
            //            }
            //        });
            //    }
            //);
            
            $('body').on(
                'click',
                '.controller.administrator .action.delete-menu',
                function(event){
                    $(this).popover({
                        placement: 'left',
                        html: true,
                        title: "Delete menu item",
                        content: '<div class="warning"><strong>Are you sure you want to delete this menu item?</strong><div class="controls text-right"><button class="btn btn-sm btn-default action close-popover">No</button><button class="btn btn-sm btn-danger action delete-menu-item">Yes</button></div></div>'
                    });
                    
                    $(this).popover('show');
                    
                    return false;
                }
            );
            
            $('body').on(
                'click',
                '.controller.administrator .action.delete-sub-menu-item',
                function(event){
                    $(this).popover({
                        placement: 'left',
                        html: true,
                        title: "Delete sub menu item",
                        content: '<div class="warning"><strong>Are you sure you want to delete this menu item?</strong><div class="controls text-right"><button class="btn btn-sm btn-default action close-popover">No</button><button class="btn btn-sm btn-danger action delete-sub-menu">Yes</button></div></div>'
                    });
                    
                    $(this).popover('show');
                    
                    return false;
                }
            );
            
            $('body').on(
                'click',
                '.controller.administrator .action.delete-menu-item',
                function(event){
                    $(this).parents('.menu-item').detach();
                    return false;
                }
            );
            
            $('body').on(
                'click',
                '.controller.administrator .action.delete-sub-menu',
                function(event){
                    $(this).parents('.sub-menu-item').detach();
                    return false;
                }
            );
            
            $('body').on(
                'click',
                '.controller.administrator .action.add-sub-menu-item',
                function(event){
                    var $popover = $(this).parents('.popover');
                    var $menu_item_item_type = $popover.find('[name="menu_item_item[menu_item_item_type]"]');
                    var $label = $popover.find('[name="menu_item_item[label]"]');
                    var $link = $popover.find('[name="menu_item_item[link]"]');
                    var $popover_trigger = $popover.prev();
                    var valid = true;
                    
                    if ($menu_item_item_type.val() != 'Divider' && $label.val() == "") {
                        $label.parents('.form-group').addClass("has-error");
                        valid = false;
                    }
                    
                    if ($menu_item_item_type.val() == "Link"){
                        if ($link.val() == "") {
                            $link.parents('.form-group').addClass("has-error");
                            valid = false;
                        }
                    }
                    
                    if (valid){
                        var menu_item_item_type = $menu_item_item_type.val();
                        var label = $label.val();
                        var link = $link.val();
                        var $menu_builder = $popover_trigger.parents('.menu-item').find('.sub-menu-items');
                        var priority = $menu_builder.children().length + 1;
                        var group = $popover_trigger.parents('.menu-item').find('.menu-group').val();
                        
                        var form_fields = '<input type="hidden" name="menu_item_item[menu_item_item_id][]" value="" />';
                        form_fields = form_fields + '<input type="hidden" name="menu_item_item[menu_item_item_type][]" value="' + menu_item_item_type + '" />';
                        form_fields = form_fields + '<input type="hidden" name="menu_item_item[priority][]" value="' + priority + '" />';
                        form_fields = form_fields + '<input type="hidden" name="menu_item_item[link][]" value="' + link + '" />';
                        form_fields = form_fields + '<input type="hidden" name="menu_item_item[label][]" value="' + label + '" />';
                        form_fields = form_fields + '<input type="hidden" name="menu_item_item[_group][]" value="' + group + '" />';
                        
                        if (menu_item_item_type == 'Link') {
                            $menu_builder.append('<div class="under-panel sub-menu-item"><div class="controls pull-right"><button class="btn btn-link action edit-sub-menu-item"><span class="fa fa-pencil"></span></button> <button class="btn btn-link action delete-sub-menu-item"><span class="fa fa-trash"></span></button></div><hr class="hidden"><span class="menu-label">' + label + '</span><span class="menu-link">(' + link + ')</span><div class="form-sub-menu-data">' + form_fields + '</div></div>');
                        }else if(menu_item_item_type == 'Heading'){
                            $menu_builder.append('<div class="under-panel sub-menu-item"><div class="controls pull-right"><button class="btn btn-link action edit-sub-menu-item"><span class="fa fa-pencil"></span></button> <button class="btn btn-link action delete-sub-menu-item"><span class="fa fa-trash"></span></button></div><hr class="hidden"><span class="menu-label">' + label + '</span><span class="menu-link hidden">(' + link + ')</span><div class="form-sub-menu-data">' + form_fields + '</div></div>');
                        }else{
                            $menu_builder.append('<div class="under-panel sub-menu-item"><div class="controls pull-right"><button class="btn btn-link action edit-sub-menu-item"><span class="fa fa-pencil"></span></button> <button class="btn btn-link action delete-sub-menu-item"><span class="fa fa-trash"></span></button></div><hr><span class="menu-label hidden">' + label + '</span><span class="menu-link hidden">(' + link + ')</span><div class="form-sub-menu-data">' + form_fields + '</div></div>');
                        }
                        
                        
                        
                        $popover_trigger.popover('destroy');
                    }
                    
                    return false;
                }
            );
            
            
            $('body').on(
                'click',
                '.controller.administrator .action.add-menu-item',
                function(event){
                    var $popover = $(this).parents('.popover');
                    var $menu_item_type = $popover.find('[name="menu_item[menu_item_type]"]');
                    var $label = $popover.find('[name="menu_item[label]"]');
                    var $link = $popover.find('[name="menu_item[link]"]');
                    var $popover_trigger = $popover.prev();
                    var valid = true;
                    
                    if ($label.val() == "") {
                        $label.parents('.form-group').addClass("has-error");
                        valid = false;
                    }
                    
                    if ($menu_item_type.val() == "Link"){
                        if ($link.val() == "") {
                            $link.parents('.form-group').addClass("has-error");
                            valid = false;
                        }
                    }
                    
                    if (valid){
                        var menu_item_type = $menu_item_type.val();
                        var label = $label.val();
                        var link = $link.val();
                        var $menu_builder = $('.menu-builder');
                        var priority = $('.menu-builder').children().length + 1;
                        var form_fields = '<input type="hidden" name="menu_item[menu_item_id][]" value="" />';
                        form_fields = form_fields + '<input type="hidden" name="menu_item[menu_item_type][]" value="' + menu_item_type + '" />';
                        form_fields = form_fields + '<input type="hidden" name="menu_item[priority][]" value="' + priority + '" />';
                        form_fields = form_fields + '<input type="hidden" name="menu_item[link][]" value="' + link + '" />';
                        form_fields = form_fields + '<input type="hidden" name="menu_item[name][]" value="menu_builder_' + priority + '" />';
                        form_fields = form_fields + '<input type="hidden" name="menu_item[label][]" value="' + label + '" />';
                        form_fields = form_fields + '<input type="hidden" class="menu-group" name="menu_item[_group][]" value="menu_builder_group-' + label + '-' + priority + '" />';
                        
                        if (menu_item_type == 'Link') {
                            $menu_builder.append('<div class="under-panel menu-item"><div class="controls pull-right"><button class="btn btn-link action edit-menu"><span class="fa fa-pencil"></span></button> <button class="btn btn-link action delete-menu"><span class="fa fa-trash"></span></button></div><span class="menu-label">' + label + '</span><span class="menu-dropdown-indicator hidden"><span class="caret"></span></span><span class="menu-link">(' + link + ')</span><div class="form-menu-data">' + form_fields + '</div><div class="sub-menu-items hidden"></div><button class="btn btn-link action new-sub-menu-item hidden"><span class="fa fa-plus"></span></button></div>');
                        }else{
                            $menu_builder.append('<div class="under-panel menu-item"><div class="controls pull-right"><button class="btn btn-link action edit-menu"><span class="fa fa-pencil"></span></button> <button class="btn btn-link action delete-menu"><span class="fa fa-trash"></span></button></div><span class="menu-label">' + label + '</span><span class="menu-dropdown-indicator"><span class="caret"></span></span><span class="menu-link hidden">(' + link + ')</span><div class="form-menu-data">' + form_fields + '</div><div class="sub-menu-items"></div><button class="btn btn-link action new-sub-menu-item"><span class="fa fa-plus"></span></button></div>');
                        }
                        
                        
                        
                        $popover_trigger.popover('destroy');
                    }
                    
                    return false;
                }
            );
            
            $('body').on(
                'click',
                '.controller.administrator .action.edit-menu-item',
                function(event){
                    var $popover = $(this).parents('.popover');
                    var $menu_item_type = $popover.find('[name="menu_item[menu_item_type]"]');
                    var $label = $popover.find('[name="menu_item[label]"]');
                    var $link = $popover.find('[name="menu_item[link]"]');
                    var $popover_trigger = $popover.prev();
                    var valid = true;
                    
                    if ($label.val() == "") {
                        $label.parents('.form-group').addClass("has-error");
                        valid = false;
                    }
                    
                    if ($menu_item_type.val() == "Link"){
                        if ($link.val() == "") {
                            $link.parents('.form-group').addClass("has-error");
                            valid = false;
                        }
                    }
                    
                    if (valid){
                        var menu_item_type = $menu_item_type.val();
                        var label = $label.val();
                        var link = $link.val();
                        var $menu_item = $popover.parents('.menu-item');
                        
                        $menu_item.find('[name="menu_item[menu_item_type][]"]').val(menu_item_type);
                        $menu_item.find('[name="menu_item[label][]"]').val(label);
                        $menu_item.find('[name="menu_item[link][]"]').val(link);
                        
                        $menu_item.find('.menu-label').empty().append(label);
                        $menu_item.find('.menu-link').empty().append("(" + link + ")");
                        
                        if (menu_item_type == 'Link') {
                            $menu_item.find('.menu-dropdown-indicator,.sub-menu-items,.action.new-sub-menu-item').addClass('hidden');
                            $menu_item.find('.menu-link').removeClass('hidden');
                        }else{
                            $menu_item.find('.menu-dropdown-indicator,.sub-menu-items,.action.new-sub-menu-item').removeClass('hidden');
                            $menu_item.find('.menu-link').addClass('hidden');
                        }
                        
                        
                        
                        $popover_trigger.popover('destroy');
                    }
                    
                    return false;
                }
            );
            
            $('body').on(
                'click',
                '.controller.administrator .action.edit-sub-menu',
                function(event){
                    var $popover = $(this).parents('.popover');
                    var $menu_item_item_type = $popover.find('[name="menu_item_item[menu_item_item_type]"]');
                    var $label = $popover.find('[name="menu_item_item[label]"]');
                    var $link = $popover.find('[name="menu_item_item[link]"]');
                    var $popover_trigger = $popover.prev();
                    var valid = true;
                    
                    if ($label.val() == "") {
                        $label.parents('.form-group').addClass("has-error");
                        valid = false;
                    }
                    
                    if ($menu_item_item_type.val() == "Link"){
                        if ($link.val() == "") {
                            $link.parents('.form-group').addClass("has-error");
                            valid = false;
                        }
                    }
                    
                    if (valid){
                        var menu_item_item_type = $menu_item_item_type.val();
                        var label = $label.val();
                        var link = $link.val();
                        var $menu_item = $popover.parents('.sub-menu-item');
                        
                        $menu_item.find('[name="menu_item_item[menu_item_item_type][]"]').val(menu_item_item_type);
                        $menu_item.find('[name="menu_item_item[label][]"]').val(label);
                        $menu_item.find('[name="menu_item_item[link][]"]').val(link);
                        
                        $menu_item.find('.menu-label').empty().append(label);
                        $menu_item.find('.menu-link').empty().append("(" + link + ")");
                        
                        if (menu_item_item_type == 'Link') {
                            $menu_item.find('hr').addClass('hidden');
                            $menu_item.find('.menu-label,.menu-link').removeClass('hidden');
                        }else if (menu_item_item_type == 'Heading'){
                            $menu_item.find('hr,.menu-link').addClass('hidden');
                            $menu_item.find('.menu-label').removeClass('hidden');
                        }else{
                            $menu_item.find('.menu-label,.menu-link').addClass('hidden');
                            $menu_item.find('hr').removeClass('hidden');
                        }
                        
                        
                        
                        $popover_trigger.popover('destroy');
                    }
                    
                    return false;
                }
            );
            
            $('body').on(
                'click',
                '.controller.administrator .action.close-popover',
                function(event){
                    var $popover = $(this).parents('.popover');
                    var $popover_trigger = $popover.prev();
                    
                    $popover_trigger.popover('destroy');
                    
                    return false;
                }
            );
            
            $('body').on(
                'change',
                '.controller.administrator [name="menu_item[menu_item_type]"]',
                function(event){
                    var $popover = $(this).parents('.popover');
                    
                    if ($(this).val() == "Link"){
                        $popover.find('[name="menu_item[link]"]').parents('.form-group').removeClass('hidden');
                    }else{
                        $popover.find('[name="menu_item[link]"]').parents('.form-group').addClass('hidden');
                    }
                }
            );
            
            $('body').on(
                'change',
                '.controller.administrator [name="menu_item_item[menu_item_item_type]"]',
                function(event){
                    var $popover = $(this).parents('.popover');
                    
                    if ($(this).val() == "Link"){
                        $popover.find('[name="menu_item_item[link]"]').parents('.form-group').removeClass('hidden');
                        $popover.find('[name="menu_item_item[label]"]').parents('.form-group').removeClass('hidden');
                    }else if($(this).val() == "Heading"){
                        $popover.find('[name="menu_item_item[link]"]').parents('.form-group').addClass('hidden');
                        $popover.find('[name="menu_item_item[label]"]').parents('.form-group').removeClass('hidden');
                    }else{
                        $popover.find('[name="menu_item_item[link]"]').parents('.form-group').addClass('hidden');
                        $popover.find('[name="menu_item_item[label]"]').parents('.form-group').addClass('hidden');
                    }
                }
            );
            
            $('body').on(
                'click',
                '.controller.administrator_menus .action.new-sub-menu-item',
                function(event){
                    $.ajax({
                        type: 'get',
                        url: '/administrator/menus/sub-menu-item-form',
                        dataType: 'html',
                        context: this,
                        success: function(data){
                            $(this).popover({
                                placement: 'right',
                                html: true,
                                title: "New sub menu item",
                                content: data
                            });
                            
                            $(this).popover('show');
                        }
                    });
                    
                    return false;
                }
            );
            
            $('.controller.administrator_menus .action.new-menu').on(
                'click',
                function(event){
                    $.ajax({
                        type: 'get',
                        url: '/administrator/menus/menu-item-form',
                        dataType: 'html',
                        context: this,
                        success: function(data){
                            $(this).popover({
                                placement: 'right',
                                html: true,
                                title: "New menu item",
                                content: data
                            });
                            
                            $(this).popover('show');
                        }
                    });
                    
                    return false;
                }
            );
            
            $('body').on(
                'click',
                '.controller.administrator_menus .action.edit-sub-menu-item',
                function(event){
                    
                    var menu_item_item_id = $(this).parents('.sub-menu-item').find('[name="menu_item_item[menu_item_item_id][]"]').val();
                    var menu_item_item_type = $(this).parents('.sub-menu-item').find('[name="menu_item_item[menu_item_item_type][]"]').val();
                    var link = $(this).parents('.sub-menu-item').find('[name="menu_item_item[link][]"]').val();
                    var name = $(this).parents('.sub-menu-item').find('[name="menu_item_item[name][]"]').val();
                    var label = $(this).parents('.sub-menu-item').find('[name="menu_item_item[label][]"]').val();
                    var group = $(this).parents('.sub-menu-item').find('[name="menu_item_item[_group][]"]').val();
                    
                    var data = {
                        'menu_item_item[menu_item_item_id]': menu_item_item_id,
                        'menu_item_item[menu_item_item_type]': menu_item_item_type,
                        'menu_item_item[link]': link,
                        'menu_item_item[name]': name,
                        'menu_item_item[label]': label,
                        'menu_item_item[_group]': group,
                    };
                    
                    $.ajax({
                        type: 'get',
                        url: '/administrator/menus/sub-menu-item-form',
                        dataType: 'html',
                        context: this,
                        data: data,
                        success: function(data){
                            $(this).popover({
                                placement: 'left',
                                html: true,
                                title: "Edit sub menu item",
                                content: data
                            });
                            
                            $(this).popover('show');
                        }
                    });
                    
                    return false;
                }
            );
            
            $('body').on(
                'click',
                '.controller.administrator_menus .action.edit-menu',
                function(event){
                    
                    var menu_item_id = $(this).parents('.menu-item').find('[name="menu_item[menu_item_id][]"]').val();
                    var menu_item_type = $(this).parents('.menu-item').find('[name="menu_item[menu_item_type][]"]').val();
                    var priority = $(this).parents('.menu-item').find('[name="menu_item[priority][]"]').val();
                    var link = $(this).parents('.menu-item').find('[name="menu_item[link][]"]').val();
                    var name = $(this).parents('.menu-item').find('[name="menu_item[name][]"]').val();
                    var label = $(this).parents('.menu-item').find('[name="menu_item[label][]"]').val();
                    var group = $(this).parents('.menu-item').find('[name="menu_item[_group][]"]').val();
                    
                    var data = {
                        'menu_item[menu_item_id]': menu_item_id,
                        'menu_item[menu_item_type]': menu_item_type,
                        'menu_item[priority]': priority,
                        'menu_item[link]': link,
                        'menu_item[name]': name,
                        'menu_item[label]': label,
                        'menu_item[_group]': group,
                    };
                    
                    $.ajax({
                        type: 'get',
                        url: '/administrator/menus/menu-item-form',
                        dataType: 'html',
                        context: this,
                        data: data,
                        success: function(data){
                            $(this).popover({
                                placement: 'left',
                                html: true,
                                title: "Edit menu item",
                                content: data
                            });
                            
                            $(this).popover('show');
                        }
                    });
                    
                    return false;
                }
            );
            
            $('.controller.administrator_menus [name="menu[menu_type_id]"]').on(
                'change',
                function(event){
                    $('.controller.administrator_menus .ajax-example').children().fadeTo('slow', 0.3);
                    administrator_menu_example();
                }
            );
            
            $('.controller.administrator_item input[name="q"]').on(
                'keyup',
                function(event){
                    clearTimeout(administrator_item_timeout);
                    administrator_item_timeout = setTimeout(administrator_item_search, 750);
                    $('.controller.administrator_item .ajax-data').children().fadeTo('slow', 0.3);
                    $('.controller.administrator_item form input[name="page"]').val('1');
                }
            );
            
            $(document).on(
                'click',
                '.controller.administrator_item .sorter',
                function(event){
                    var $this = $(this);
                    var sort_field = $this.attr('data-value');
                    var state = $this.attr('data-state');
                    console.log('Current state: ' + state);
                    if (state == 'inactive'){
                        state = 'asc';
                        $('.controller.administrator_item form input[name="sort_order"]').val(sort_field);
                        $('.controller.administrator_item form input[name="sort_direction"]').val(state);
                    }else if (state == 'asc') {
                        state = 'desc';
                        $('.controller.administrator_item form input[name="sort_order"]').val(sort_field);
                        $('.controller.administrator_item form input[name="sort_direction"]').val(state);
                    }else if (state == 'desc') {
                        state = 'inactive';
                        $('.controller.administrator_item form input[name="sort_order"]').val('');
                        $('.controller.administrator_item form input[name="sort_direction"]').val('asc');
                    }
                    
                    $('.controller.administrator_item .ajax-data').children().fadeTo('slow', 0.3);
                    administrator_item_search();
                }
            );
            
            $(document).on(
                'click',
                '.controller.administrator_item a.pager-page',
                function(event){
                    var $this = $(this);
                    var page = parseInt($('.controller.administrator_item form input[name="page"]').val());
                    
                    if ($this.parent().hasClass('disabled') == false) {
                        if ($this.hasClass('pager-previous')){
                            if (page > 1) {
                                page = page - 1;
                                $('.controller.administrator_item form input[name="page"]').val(page);
                                $('.controller.administrator_item .ajax-data').children().fadeTo('slow', 0.3);
                                administrator_item_search();
                            }
                        }else if ($this.hasClass('pager-next')){
                            page = page + 1;
                            $('.controller.administrator_item form input[name="page"]').val(page);
                            $('.controller.administrator_item .ajax-data').children().fadeTo('slow', 0.3);
                            administrator_item_search();
                        }else{
                            page = $this.text();
                            $('.controller.administrator_item form input[name="page"]').val(page);
                            $('.controller.administrator_item .ajax-data').children().fadeTo('slow', 0.3);
                            administrator_item_search();
                        }
                    }
                }
            );
            
            $('.controller.administrator_item .filters input[type="checkbox"], .controller.administrator_item .filters select').on(
                'change',
                function(event){
                    $('.controller.administrator_item form input[name="page"]').val(1);
                    $('.controller.administrator_item .ajax-data').children().fadeTo('slow', 0.3);
                    administrator_item_search();
                }
            );
            
            function administrator_item_search(){
                clearTimeout(administrator_item_timeout);
                var value = $('.controller.administrator_item input[name="q"]').val();
                //if (value == ""){
                //    //$('.ats.content_holder').empty().append('<h3>Start typing to search</h3>');
                //    console.log('Type something');
                //}else if (value.length <= 3){
                //    $('.ats.content_holder').empty().append('<h3>Too many results, please continue to type</h3>');
                //    console.log('Type more');
                //}else{
                    var $icon = $('<span class="fa fa-circle-o-notch fa-spin fa-lg form-control-feedback"></span>');
                    $('.controller.administrator_item input[name="q"]').parents('.form-group').addClass('has-feedback').append($icon);
                    var data = $('.controller.administrator_item .view.form').serialize();
                    
                    $.ajax({
                        url: $('.controller.administrator_item .view.form').attr('action') + '/data',
                        data: data,
                        success: function(data){
                            $('.controller.administrator_item .ajax-data').fadeOut("slow", function(){
                                $('.controller.administrator_item input[name="q"]').parents('.form-group').removeClass('has-feedback');
                                $('.controller.administrator_item input[name="q"]').parents('.form-group').find('span.fa').detach();
                                //$('.controller.administrator_item .ajax-data').css('opacity', 1);
                                $('.controller.administrator_item .ajax-data').children().replaceWith(data);
                                $('.controller.administrator_item .ajax-data').fadeIn("slow");
                            });
                        }
                    });
                    
                    //$('.ats.content_holder').empty().append('<i class="view icon fa fa-cog fa-spin"></i>');
                    //var url = "/administrator/ats/search/results?q=" + encodeURIComponent(value);
                    //$('.ats.content_holder').load(url);
                    //console.log('Search');
                    
                //}
            }
            
            function administrator_menu_example(){
                var value = $('.controller.administrator_menus [name="menu[menu_type_id]"]').val();
                
                $.ajax({
                    url: '/administrator/menus/example?menu[menu_type_id]=' + value,
                    success: function(data){
                        $('.controller.administrator_menus .ajax-example').fadeOut("slow", function(){
                            $('.controller.administrator_menus .ajax-example').children().replaceWith(data);
                            $('.controller.administrator_menus .ajax-example').fadeIn("slow");
                        });
                    }
                });
            }
        }
    );
    
    
    
})(jQuery);