(function($) {
    var total_today = 0.00;
    var total_one_off = 0.00;
    var total_monthly = 0;

    function displayTotalPrice() {
        $(".total-one-off p").text("$" + total_one_off.toFixed(2));
        $(".total-to-pay p").text("$" + total_today.toFixed(2));
        $(".total-monthly p").text("$" + total_monthly.toFixed(2));
    }

    function addProduct(item, product_id) {
        var title = $(item).find("h1");
        var description = $(item).find("h2");
        var price = $(item).find(".product-price");
        var data_no = $(item).attr("data-no");
        var input_text = "<div class='summary-item' data-id='"+product_id+"' data-no='"+data_no+"'><div class='remove-order'></div><div style='width: 100%;'><h1>"+title.html()+"</h1><h2>"+description.html()+"</h2></div><p>"+price.html()+"</p></div>";
        $(".order-summary").append(input_text);
        var sd = price.text().match(/[0-9.]+/g);
        
        total_today += parseFloat(sd);
        if (price.text().indexOf("mth") > 0) {
            total_monthly += parseFloat(sd);
        } else {
            total_one_off += parseFloat(sd);
        }
        displayTotalPrice();
    }

    function removeProdct(item, product_id) {
        var summary = $(".summary-item");
        for (var i = 0; i < summary.length; i ++) {
            var id = $(summary[i]).attr("data-id");
            if (product_id == id) {
                $(summary[i]).remove();
                break;
            }
        }

        var price = $(item).find(".product-price");
        var sd = price.text().match(/[0-9.]+/g);
        
        total_today -= parseFloat(sd);
        if (price.text().indexOf("mth") > 0) {
            total_monthly -= parseFloat(sd);
        } else {
            total_one_off -= parseFloat(sd);
        }
        displayTotalPrice();
    }
    
    function addToCart(product_id) {
        $.get({url:'https://airtel.net.au/?post_type=product&add-to-cart=' + product_id, async:false}, function() {
        });
    }

    function removeProductOnCart(product_id) {
        $.ajax({
            type: "POST",
            url: 'https://airtel.net.au/wp-admin/admin-ajax.php',
            async: false,
            data: {action : 'remove_item_from_cart', product_id : product_id},
            success: function (res) {
                if (res) {
                }
            }
        });   
    }

    function loadProductsFromCart() {
        $.ajax({
            type: "POST",
            url: 'https://airtel.net.au/wp-admin/admin-ajax.php',
            async: false,
            data: {action : 'load_item_from_cart'},
            success: function (res) {
                if (res) {
                    var result = JSON.parse(res);
                    
                    for (var i = 0; i < result.ID.length; i ++) {
                        var product = $("body").find(".product-item[data-id='"+result.ID[i]+"']");
                        $(product).addClass("selected");
                        $(product).find(".select-product-item").text("Selected");
                        addProduct(product, result.ID[i]);
                    }
                }
            }
        });   
    }

    function setCookie(cname, cvalue, exdays) {
        const d = new Date();
        d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
        let expires = "expires="+d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }
  
    function getCookie(cname) {
        let name = cname + "=";
        let ca = document.cookie.split(';');
        for(let i = 0; i < ca.length; i++) {
          let c = ca[i];
          while (c.charAt(0) == ' ') {
            c = c.substring(1);
          }
          if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
          }
        }
        return "";
    }

    // Search Address
    var adresDB = new Bloodhound({
        datumTokenizer: function (datum) {
        return Bloodhound.tokenizers.whitespace(datum.value);
    },
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    remote: {
        //url: '/aus_addr.php/d?q=%QUERY',
        url: '/aus_addr.php/d?address=%QUERY',
            filter: function (adresDB) {
                console.log(adresDB);
                return jQuery.map(adresDB.features, function (mm) {
                    res =  {
                        id:                     mm.properties.addressId,
                        highlighted:            mm.properties.formattedAddress,
                        address:                mm.properties.formattedAddress,
                        flat_number:            mm.properties.complexUnitNumber,
                        number_first:           mm.properties.streetNumber1,
                        street_name:            mm.properties.streetName,
                        street_type_code:       mm.properties.streetType,
                        locality_name:          mm.properties.localityName,
                        state_abbreviation:     mm.properties.stateTerritory,
                        postcode:               mm.properties.postcode,
                    };
                    console.log(res);
                    return res;
                });
              },
        wildcard: '%QUERY'
    }
    });
    

    // Initialize the Bloodhound suggestion engine
    adresDB.initialize();
    
    // Instantiate the Typeahead UI
    jQuery('#location').typeahead(null, {
        name: 'make-model',
        highlight: true,
        displayKey: 'address',
        source: adresDB.ttAdapter(),
                templates: {
    	      notFound: [
    	        '<div class="empty-message">',
    	          '&nbsp;address not found! ',
    	        '</div>'
    	      ].join('\n'),
            suggestion: function (adresDB) {
                return '<div>' + adresDB.highlighted + '</div>';
            }
    
        }
    });

	$('.close-search').click(function() {
		$('#location').val('')
        $("#row_qualify_fail").addClass("hidden");
        $("#row_qualify_success").addClass("hidden");		
	});
	
	$('.search-location').on('typeahead:selected', function (e, datum) {
        var request = $.ajax({
            type: "POST",
            url: "https://service.airtel.net.au/api/find_service",
            data: JSON.stringify({
                serviceProvider: "NBN",
                unitNumber: datum.flat_number,
                streetNumber: datum.number_first,
                streetName: datum.street_name,
                streetType: datum.street_type_code,
                suburb: datum.locality_name,
                state: datum.state_abbreviation,
                postcode: datum.postcode,
            }),
            dataType: "json",
            headers: { 
                'Accept': 'application/json',
                'Content-Type': 'application/json' 
            },            
        });

        $("#row_qualify_fail").addClass("hidden");
        $("#row_qualify_success").addClass("hidden");

        $("#row_checking").removeClass("hidden");
        
		$.when(request).done(function(r1) {
			console.log('AAPT:');
			console.log(r1);
			$("#row_checking").addClass("hidden");
			data = r1;
            if (data.serviceProviderLocationList !== undefined) {
                var nbnLocationList = data.serviceProviderLocationList.filter(x => x.serviceProvider === 'NBN');
                if (nbnLocationList.length > 0) {
                    var locationList = nbnLocationList[0].locationList;
                    if (locationList.addressInformation !== undefined) {
                        var locationId = locationList.addressInformation[0].locationId;
                        setCookie("location_id", locationId);
                        setCookie("display_address", locationList.addressInformation[0].displayAddress);
                        var qualify_service_request = $.ajax({
                            type: "POST",
                            url: "https://service.airtel.net.au/api/qualify_service",
                            data: JSON.stringify({
                              serviceProvider: "NBN",
                              locationId,
                              productType: "NWB",
                            }),
                            dataType: "json",
                            headers: {
                              Accept: "application/json",
                              "Content-Type": "application/json",
                            },
                        });
                      
                        qualify_service_request.done(function (data) {
                          console.log(data);
                          if (data.qualificationID && data.siteDetails) {
                            if (data.siteDetails.nbnLocationID && data.accessQualificationList.length > 0) {
                              var availableServices = data.accessQualificationList.filter(v => v.qualificationResult === 'PASS');
                              if (availableServices.length > 0) {
                                console.log(availableServices);
                                setCookie("qualification_id", data.qualificationID);
                                $("#row_qualify_success").removeClass("hidden");
                                return;
                              }
                            }
                          }
                          $("#row_qualify_fail").removeClass("hidden");
                        })
                        return;
                    }
                }
                $("#row_qualify_fail").removeClass("hidden");
            } else {
                $("#row_qualify_fail").removeClass("hidden");
            }
		});
		
        /*request.done(function( data ) {
            $("#row_checking").addClass("hidden");
            if (data.status == "200") {
                if (data.locationID === undefined) {
                    $("#row_qualify_fail").removeClass("hidden");
                } else {
                    $("#row_qualify_success").removeClass("hidden");
                }
            } else {
                $("#row_qualify_fail").removeClass("hidden");
            }
            console.log(data)
        });
         
        request.fail(function( jqXHR, textStatus ) {
            $("#row_checking").addClass("hidden");
            $("#row_qualify_fail").removeClass("hidden");
            console.log( "Request failed: " + textStatus );
        });*/
        
        
    });
    
    $(".build-plan-button").click(function() {
        var next_section = $(".category-section");
        $('html, body').animate({
            scrollTop: $(next_section).offset().top - 100  
        }, 1000);
    });


	$( ".select-product-item" ).click(function() {
        var product = $(this).parent();
        var category_idx = parseInt($(product).attr("data-no"));
        
        if (category_idx < 4) {
            category_idx = 0;
        } else if (category_idx > 3 && category_idx < 7) {
            category_idx = 1;
        } else if (category_idx > 6 && category_idx < 10) {
            category_idx = 2;
        } else {
            category_idx = 3;
        }

        var category = $(".category-section")[category_idx];

        if (category_idx < 3) {
            var next_section = $(".category-section")[category_idx + 1];
        } else {
            var next_section = $(".order-summary-section");
        }
        
        if ($(product).hasClass("selected")) {
            var product_id = $(product).attr("data-id");
            removeProductOnCart(product_id);
            removeProdct(product, product_id);
            $(product).removeClass("selected");
            $(this).text("Select");
        } else {
            var products = $(category).find(".product-item");
            for (var i = 0; i < products.length; i ++) {
                if ($(products[i]).hasClass("selected")) {
                    var product_id = $(products[i]).attr("data-id");
                    removeProductOnCart(product_id);
                    removeProdct(products[i], product_id);
                    $(products[i]).removeClass("selected");
                    $(products[i]).find(".select-product-item").text("Select");
                }
            }

            var product_id = $(product).attr("data-id");

            addToCart(product_id);
            addProduct(product, product_id);
            $(product).addClass("selected");
            $(this).text("Selected");
            $('html, body').animate({
                scrollTop: $(next_section).offset().top - 100  
            }, 1000);
        }
    });

    $(document).on( 'click', '.remove-order', function () {
        var data_id = $(this).parent().attr("data-id");
        var data_no = parseFloat($(this).parent().attr("data-no"));
        var item = $(".product-item")[data_no];

        $(this).parent().remove();

        var price = $(item).find(".product-price");
        var sd = price.text().match(/[0-9.]+/g);
        
        total_today -= parseFloat(sd);
        if (price.text().indexOf("mth") > 0) {
            total_monthly -= parseFloat(sd);
        } else {
            total_one_off -= parseFloat(sd);
        }
        removeProductOnCart(data_id);
        $(item).removeClass("selected");
        $(item).find(".select-product-item").text("Select");
        displayTotalPrice();
    });

    $( document ).ready(function() {
        loadProductsFromCart();
    });
	
})( jQuery );
