function show_price_offer() {
    $('.priceOffer').toggleClass("hidden");
}

function addCustField(name) {
    alert(name);
    name == '' ? name : document.getElementById("txtAttributeSearch").value;
    var attHTML =
        `
                <div class="form-group col-md-12" >          
                      <div class="form-group col-md-12" style="display:flex">
                        <input type="text" id=` + name + ` placeholder=` + name + ` name=` + name + ` class="form-control"/>
                        <span class="glyphicon glyphicon-trash" onclick="delCustField(` + name + `)"></span>
                      </div> 
                    
               </div>
               ` +
        '</div></div></div>';
    $("#product-details-section").append(attHTML);
}

function delCustField(id) {
    $("#product_details_" + id).remove();
}

$(document).ready(function () {


    $('.tab-header a').on('click' , function (){
        $('.Description').toggleClass('active');
        $('.Specifications').toggleClass('active');

    });

    /**
     * Start Add product page js
     */
    $("#file_upload").change(function () {
        readURL(this);
    });

    function readURL(input) {
        if (input.files) {
            //get current image count
            var images_count = $('#images_count').val();
            if(images_count > 0){
                if((Number(images_count) + Number(input.files.length)) <= 6){
                    for (i = 0; i < input.files.length; i++) {
                        $('.blah-' + (Number(images_count)+1)).attr('src', URL.createObjectURL(event.target.files[i]));
                    };
                    $('#images_count').val(Number(images_count) + Number(1));
    
                }else{
                    alert('maximun images is 6 images only');
    
                }
            }else {
                for (i = 0; i < input.files.length; i++) {
                    $('.blah-' + i).attr('src', URL.createObjectURL(event.target.files[i]));
                };
            }
        }
    }
    function delete_image (iteration) {
        $('.blah-'+iteration).attr('src', null)
    }

    $(document).ready(function () {
        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            var href = $(e.target).attr('href');
            var $curr = $(".checkout-bar  a[href='" + href + "']").parent();

            $('.checkout-bar li').removeClass();

            $curr.addClass("active");
            $curr.prevAll().addClass("visited");
        });

        $("[data-toggle=popover]").popover({
            html: true,
            content: function () {
                var content = $(this).attr("data-popover-content");
                return $(content).children(".popover-body").html();
            },
            title: function () {
                var title = $(this).attr("data-popover-content");
                return $(title).children(".popover-heading").html();
            }
        });


        $(document).on('keyup', "[id^=txtAttributeSearch]", function () {
            var currentValue = $(this).val();
            var resDiv = $(this).closest("div").children('div').text("");
            $.ajax({
                url: '/product/productattribute?q=' + $(this).val(),
                dataType: 'json',
                success: function (response) {
                    //console.log(response);
                    $.each(response, function (index, value) {
                        //var ds = $.parseJSON(index);
                        var key = Object.keys(value)[1];
                        var name = this[key];
                        key = Object.keys(value)[0];
                        var id = this[key];
                        resDiv.append(
                            `<div class="btn navy searchedAttributeText" onclick="addCustField('` +
                            value.attribute +
                            `')">` + name + `</div><br> `
                        );

                    });

                },

            });
            resDiv.append(`<div class='addAttributeButton' onclick="addCustField('` + currentValue + `')" >Add New </div>`);
        });

        $('#product_category').select2({
            placeholder: "Select Category",
        });
        $('#product_subCategory').select2({
            placeholder: "Select a Sub Category",
        });
        $('#product_status').select2({
            placeholder: "Status",
        });
        $('#product_currency').select2({
            placeholder: "Currency",
        });
        $('#attribute_brand').select2({
            placeholder: "Brand",
        });
        $('#attribute_product_weight_unit').select2({
            placeholder: "Weight",
        });
        $('#attribute_product_period').select2({
            placeholder: "Period",
        });
        $('#attribute_product_volume_unit').select2({
            placeholder: "Unit",
        });
        $('#attribute_product_type').select2({
            placeholder: "Type",
        });
        $('#attribute_product_country_of_origin').select2({
            placeholder: "Country Of Origin",
        });
        

        $('.select_category').each(function (i, elm) {
            $(elm).select2({
                placeholder: 'Search for a category',
                ajax: {
                    url: '/product/productcategory',
                    dataType: 'json',
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.category,
                                    id: item.id
                                }
                            })
                        };
                    },
                    cache: true
                }
            });

            var product_category = $(elm).siblings('#selected_product_category').val();
            var category_name = $(elm).siblings('#selected_category_name').val();

            if ($(elm).find("option[value='" + category_name + "']").length) {
                $(elm).val(category_name).trigger('change');
            } else {
                // Create a DOM Option and pre-select by default
                var newOption = new Option(category_name, product_category, true, true);
                // Append it to the select
                $(elm).append(newOption).trigger('change');
            }
        });

        $("#product_category").change(function () {
            //alert($('select[name=product_category]').val());
            //alert($('select[name=product_category]').text());
            $('#selected_product_category').val($('select[name=product_category]').val());
            $('#selected_category_name').val($('select[name=product_category]').text());
        }); // $("#product_category").change end

    });




    function progressHandler(event, prg) {
        //console.log('as' + event.loaded);
        var percent = Math.round((event.loaded / event.total) * 100);
        console.log(percent);
        prg.value = percent;
    }

    function UploadcompleteHandler(event, prg, img, imgsrc) {
        prg.className = 'hidden';
        console.log(event.target.response);
        let obj = JSON.parse(event.target.response);
        //console.log(obj.path);
        //return;
        //prg.value = 0;
        if ($('#images').val().length == 0) {
            $('#images').val(obj.id);
        } else {
            $('#images').val($('#images').val() + ',' + obj.id);
        }
        img.src = "/" + obj.path;
        imgsrc.value = "/" + obj.path;
        // UploadcompleteHandler();
    }

    function checkFileSize(fileSize, maxsize = 2097152) {
        if (fileSize > maxsize) {
            alert('Maximum file size should be ' + parseInt(maxsize / 1000000) + 'M');
            return false;
        }
        return true;
    }

    function checkFileType(fileType) {
        if (fileType == '') {
            var plainType = '';
        } else {
            var plainType = fileType.split('/')[1];
        }
        if ($.inArray(plainType.toLowerCase(), ['jpeg', 'jpg', 'png']) == -1) {
            alert('Only JPEG, JPG, PNG files are allowed');
            return false;
        }
        return true;
    }




    /**
     * End Add product page js
     */

    $('#sidebar-menu').click(function () {
        var buttonClass = $('.sidebar-menu i');
        $('.sidebar-wrapper').toggleClass('sidebar-xs');
        $('body').toggleClass('nav-opened');

        $('.sidebar-menu i').toggleClass("times-icon");
    });
    $('.button-checkbox').each(function () {

        // Settings
        $button = $widget.find('button'),
            $checkbox = $widget.find('input:checkbox'),
            color = $button.data('color'),
            settings = {
                on: {
                    icon: 'glyphicon glyphicon-check'
                },
                off: {
                    icon: 'glyphicon glyphicon-unchecked'
                }
            };

        // Event Handlers
        $button.on('click', function () {
            $checkbox.prop('checked', !$checkbox.is(':checked'));
            $checkbox.triggerHandler('change');
            updateDisplay();
        });
        $checkbox.on('change', function () {
            updateDisplay();
        });

        // Actions
        function updateDisplay() {
            var isChecked = $checkbox.is(':checked');

            // Set the button's state
            $button.data('state', (isChecked) ? "on" : "off");

            // Set the button's icon
            $button.find('.state-icon')
                .removeClass()
                .addClass('state-icon ' + settings[$button.data('state')].icon);

            // Update the button's color
            if (isChecked) {
                $button
                    .removeClass('btn-default')
                    .addClass('btn-' + color + ' active');
            } else {
                $button
                    .removeClass('btn-' + color + ' active')
                    .addClass('btn-default');
            }
        }

        // Initialization
        function init() {

            updateDisplay();

            // Inject the icon if applicable
            if ($button.find('.state-icon').length == 0) {
                $button.prepend('<i class="state-icon ' + settings[$button.data('state')].icon + '"></i> ');
            }
        }
        init();
    });
});