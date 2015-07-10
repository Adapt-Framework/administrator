(function($){
    /*
     * Add search to controller_administrator_item
     */
    $(document).ready(
        function(){
            var administrator_item_timeout = null;
            
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
        }
    );
    
    
    
})(jQuery);