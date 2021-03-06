var AsyncPostNClick =  {

    //var $myself = $('div.modal-content');
    //var $as;
    //var $clickedRow;
    //var $modal;

    myselfIdentifier:'', //the identifier of myself (e.g. a div surrounding me)
    formIdentifier:'', //the identifier of the form that is stored inside the element that should behave asynchronous
    linkIdentifier:'', //the identifier of all the links stored inside the element that should behave asynchronous
    additionalFunction:[function(){}], //array of functions
    formSubmitSuccessCallback:function(){},

    postForm: function($form, callback) {
        var values = {};
        $.each($form.serializeArray(), function (i, field) {
            values[field.name] = field.value;
        });
        $.ajax({
            type: $form.attr('method'),
            url: $form.attr('action'),
            data: values,
            evalScripts: true,
            success: function (data) {
                callback(data);
            }
        });
    },

    /**
     *
     * @param $form
     *
     * initiate graphical elements needed to perform ajax calls, ie. the load icon
     * perform/initiate any additional function callbacks (stored in the additionalFunction array)
     */
    initAsyncLayout: function ($form) {
        var $loadIcon = $('span#loading');
        if ($loadIcon.length === 0) {
            $form.append("<span id='loading' class='fa fa-spinner'></span>");
            $loadIcon = $('span#loading');
            $loadIcon.hide();
        }

        $(document)
            .ajaxStart(function () {
                //$myself.addClass('ui-widget-overlay');
                $form.attr('disabled', true);
                $form.find(':input').attr('disabled', true);
                $loadIcon.show();
            })
            .ajaxStop(function () {
                //$myself.removeClass('ui-widget-overlay');
                $form.attr('disabled', false);
                $form.find(':input').attr('disabled', false);
                $loadIcon.hide();
            });

        for(var i=0;i<this.additionalFunction.length;i++){
            this.additionalFunction[i]();
        }
    },

    allowAsyncSubmit: function () {
        var $myself=$(this.myselfIdentifier);
        var $form=$(this.formIdentifier);
        this.initAsyncLayout($form);
        var that=this;
        $form.submit(function (e) {
            e.preventDefault();
            that.postForm($(this), function (response) {
                $myself.html(response);
                that.formSubmitSuccessCallback();
                that.allowAsyncSubmit();
                that.allowAsyncLinks();
                //$form=$(that.formIdentifier);
                //$as = $('ul.pagination a');
                //allowAsyncLinks($myself, $as);
            });
            return false;
        });
    },

    allowAsyncLink: function ($myself, $a) {
        this.initAsyncLayout($form);
        var that=this;
        $a.click(function (e) {
            e.preventDefault();
            $.ajax({
                //type: $form.attr('method'),
                url: $a[0].href,
                evalScripts: true,
                success: function (response) {
                    $myself.html(response);
                    that.allowAsyncSubmit();
                    that.allowAsyncLinks();
                    //$as = $('ul.pagination a');
                    //var $as = $(that.linkIdentifier);
                }
            });
            return false;
        });
    },

    allowAsyncLinks: function () {
        var $myself=$(this.myselfIdentifier);
        var $as = $(this.linkIdentifier);
        var that=this;
        $as.each(function (i, e) {
            var $a = $($as[i]);
            that.allowAsyncLink($myself, $a);
        });
    }

    /*$(document).ready(function () {
     var $form = $('form#observationfilterform');
     var $as = $('ul.pagination a');
     //$modal = $($myself.parents(".modal")[0]);
     //console.log($myself);
     allowAsyncSubmit($myself, $form);
     allowAsyncLinks($myself, $as);
     });*/
};