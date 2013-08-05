
<div class="content" style="float: left;"></div>
<!-- HANDLEBARS TEMPLATE	-->
<script id="items-template" type="text/x-handlebars-template">
    {{#each data}}
    <article class="box {{feed_type}}" style="float: left; ">
        <a href="{{url}}" target="_blank">
            <figure>
                <img src="{{hero_image}}" width="270" height="270"/>
            </figure>
        </a>
    </article>
    {{/each}}
</script>
<script src="min/scripts-min.js"></script>
<script>

    function infiniteScroll(opts) {

        var jsonResponse,
            items,
            page_num = 0,
            defaults = {
                handlebars_template: '#items-template',
                restype: 'json',
                reqtype: 'GET',
                url: 'instagram.json'
            };

        this.opts = (typeof opts != 'undefined') ? opts: defaults;

        /**
         *  Make the synchronous XMLHttpRequest and reaturn the data
        */

        this.getData = function () {
            var xhr = new XMLHttpRequest();
            xhr.open(this.opts.reqtype, this.opts.url, false);
            xhr.send();
            switch(this.opts.restype){
                case 'json':
                    jsonResponse = xhr.responseText;
                    this.items = JSON.parse(jsonResponse);
                    this.page_num = 0;
                    this.renderItems(0);
                break;
            }
        }

        this.renderItems = function(page_num) {
            Handlebars.registerHelper('each', function (context, options) {
                var ret = "";
                for (var i = 0, j = context.length; i < j; i++) {
                    ret = ret + options.fn(context[i]);
                }
                return ret;
            });
            var source = $(this.opts.handlebars_template).html();
            var template = Handlebars.compile(source);
            var hb_context = typeof this.items[this.page_num] == 'undefined' ? false: this.items[this.page_num];

            if(hb_context !==false){
                this.page_num++;
                $(template(hb_context)).appendTo('.content');
            }


        }
        this.loadItems = function(){
            this.getData();
        }
    }

    var is = new infiniteScroll();
    is.loadItems();
    $('.box:last-child').live('inview', function (event, isVisible) {
        is.renderItems(is.page_num);
    });

</script>
