var TableGenerator = function() {
    var engine = this;
    var ajaxInterval = null;

    this.getAjaxTable = function(route, request, container) {
        container.find('progress').css('display', 'block');

        $.ajax({
            xhr: function() {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener("progress", function(evt) {
                    if (evt.lengthComputable) {
                        var percentComplete = evt.loaded / evt.total;

                    }
                }, false);

                xhr.addEventListener("progress", function(evt) {
                    if (evt.lengthComputable) {
                        var percentComplete = evt.loaded / evt.total;


                    }
                }, false);

                return xhr;
            },
            type: 'POST',
            url: route,
            data: request,
            success: function(data){
                if (null !== data.error || null === data.html) {
                    alert('Nie udało się załadować danych :(');
                    console.log(data);

                    return false;
                }

                container.find('.data').replaceWith($(data.html).find('.data'));
                container.find('progress').css('display', 'none');
            }
        });
    };

    this.getRequest = function(container) {
        var tableName = container.find('input[name="table_name"]').val();

        return {
            'name': tableName,
            'class': container.find('input[name="table_class"]').val(),
            'parameters': container.find('input[name="table_properties"]').val(),
            'filters': container.find('form.filters').serialize(),
            'items': container.find('input[name="table-items"]').val(),
            'export': container.find('input[name="table-export"]').val()
        };
    };

    this.search = function(search) {
        var route = search.closest('form').attr('action');
        var container = search.closest('.table-container');

        engine.getAjaxTable(
            route,
            engine.getRequest(container),
            container
        );
    };

    this.goToPage = function(search) {
        var container  = search.closest('.table-container');
        var route      = search.closest('.table-go-to-page').data('href');
        var pageNumber = container.find('input[name="actual-page"]').val();

        if (0 >= pageNumber) {
            alert("Numer strony musi być większy od 0!");

            return false;
        }

        route = route.replace(0, pageNumber);

        engine.getAjaxTable(route, engine.getRequest(container), container);
    };

    this.init = function() {
        $(document.body).on('click', '.table-container .pagination button.btn-pagination', function() {
            var container = $(this).closest('.table-container');
            var route = $(this).data('href');

            engine.getAjaxTable(route, engine.getRequest(container), container);
        });

        $(document.body).on('click', '.table-container .pagination .table-items ul li a', function(e) {
            e.preventDefault();

            var route = $(this).attr('href');
            var items = $(this).data('value');
            var container = $(this).closest('.table-container');
            container.find('input[name="table-items"]').val(items);

            engine.getAjaxTable(
                route,
                engine.getRequest(container),
                container
            );
        });

        $(document.body).on('input', '.table-container form.filters input.table-filter', function() {
            clearTimeout(ajaxInterval);
            var filter = $(this);

            ajaxInterval = setTimeout(function(filter) {
                engine.search(filter);
            }, 600, filter);
        });

        $(document.body).on('click', '.table-container form.filters button.table-search-button', function() {
            engine.search($(this));
        });

        $(document.body).on('keyup', '.table-container form.filters input[name="search"]', function(e) {
            if (e.which === 13 || e.keyCode === 13) {
                engine.search($(this));
            }

            return false;
        });

        $(document.body).on('click', '.table-container .table-go-to-page button', function() {
            engine.goToPage($(this));
        });

        $(document.body).on('keyup', '.table-container .table-go-to-page input[name="actual-page"]', function(e) {
            if (e.which === 13 || e.keyCode === 13) {
                engine.goToPage($(this));
            }
        });

        $(document.body).on('keyup', '.table-container form.filters input.table-filter', function(e) {
            if (e.which === 13 || e.keyCode === 13) {
                engine.search($(this));
            }

            return false;
        });
    };
};