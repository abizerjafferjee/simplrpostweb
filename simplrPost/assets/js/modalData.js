$baseUrl = 'http://test.code-apex.com/simplrPost/';
$siteUrl = $baseUrl + 'index.php/';
$url = $siteUrl + 'Admin/Admin/';
$uploads = $baseUrl + 'uploads/';
$onErrorString = "this.onerror=null;this.src='" + $baseUrl + "assets/img/building_placeholder.png'";
$qrImage = "this.onerror=null;this.src='http://www.v3b.com/wp-content/uploads/2011/11/QR_Code.jpg'";
// http: //www.v3b.com/wp-content/uploads/2011/11/QR_Code.jpg

    function showModalData(id) {
        $.ajax({
            type: "post",
            url: $url + 'getBusinessDataInModal',
            data: {
                'addressId': id
            },
            success: function(data) {
                $('#publicAddressContactNumber').html('');
                $('#publicAddressServices').html('');
                $('#imageSlider').html('');
                $('#logoImage').html('');
                $('#publicAddressImages').html('');
                $('#publicAddressWeekDays').html('');
                $('#socialIcons').html('');
                data = JSON.parse(data);
                $address = data.address[0];
                $weekDays = data.weekDays;

                $('#logoImage').append("<img data-enlargable style='cursor:zoom-in;border-radius:50%;' class='address-img dynamicImages' src='"+$uploads + $address.logoURL+"' onerror='this.onerror=null;this.src=\""+$baseUrl+"assets/img/building_placeholder.png\"'>");
                $('#viewAddressReports').attr('href', $siteUrl + 'business-reports/' + encodeURIComponent(window.btoa($address.addressId)));
                if($address.shortName == ''){
                    $('#publicAddressName').html('Not available');
                } else {
                    $('#publicAddressName').html($address.shortName);
                }
                if($address.categoryName == ''){
                    $('#publicAddressCategory').html('Not available');
                } else {
                    $('#publicAddressCategory').html($address.categoryName);
                }
                if($address.address == ''){
                    $('#publicAddress').html('Not available');
                } else {
                    $('#publicAddress').html($address.address);
                }
                if($address.landmark == ''){
                    $('#landmark').html('Not available');
                } else {
                    $('#landmark').html($address.landmark);
                }
                if($address.plusCode == ''){
                    $('#publicAddressPlusCode').html('Not available');
                } else {
                    $('#publicAddressPlusCode').html($address.plusCode);
                }
                $('#qrImage').html('<img onerror="' + $qrImage + '" src="' + $uploads + $address.qrCodeURL + '" id="qrImage">');
                $('#viewOnMapButton').attr('href', 'https://www.google.com/maps/search/?api=1&query=' + $address.latitude + ',' + $address.longitude);

                if ($address.contactNumbers != null) {
                    $arrNumbers = $address.contactNumbers.split(',');
                    $arrNumbers.forEach(function(number) {
                        $('#publicAddressContactNumber').append('<p class="text-muted h4 font-weight-300">' + number + '</p>');
                    });
                } else {
                    $('#publicAddressContactNumber').append('<p class="text-muted h4 font-weight-300">Not available</p>');
                }

                $('#publicAddressEmailId').html($address.emailId);
                if($address.facebookURL == ''){
                    $('#socialIcons').append('<a id="facebook" target="_blank" disabled style="cursor: not-allowed"><i class="fab fa-facebook-f" ></i></a>');
                }else{
                    $('#socialIcons').append('<a href="'+$address.facebookURL+'" id="facebook" target="_blank"><i class="fab fa-facebook-f"></i></a>');
                }
                if($address.twitterURL == ''){
                    $('#socialIcons').append('<a id="twitter" target="_blank" disabled style="cursor: not-allowed"><i class="fab fa-twitter"></i></a>');
                }else{
                    $('#socialIcons').append('<a href="'+$address.twitterURL+'" id="twitter" target="_blank"><i class="fab fa-twitter"></i></i></a>');
                }
                if($address.linkedInURL == ''){
                    $('#socialIcons').append('<a id="linkedin" target="_blank" disabled style="cursor: not-allowed"><i class="fab fa-linkedin-in"></i></a>');
                }else{
                    $('#socialIcons').append('<a href="'+$address.linkedInURL+'" id="linkedin" target="_blank"><i class="fab fa-linkedin-in"></i></a>');
                }
                if($address.instagramURL == ''){
                    $('#socialIcons').append('<a id="instagram" target="_blank" disabled style="cursor: not-allowed"><i class="fab fa-instagram"></i></a>');
                }else{
                    $('#socialIcons').append('<a href="'+$address.instagramURL+'" id="instagram" target="_blank"><i class="fab fa-instagram"></i></a>');
                }
                if($address.websiteURL == ''){
                    $('#socialIcons').append('<a id="instagram" target="_blank" disabled style="cursor: not-allowed"><img src="'+$baseUrl+'assets/img/icons/web-link.png" alt="" class="social-img"></a>');
                }else{
                    $('#socialIcons').append('<a href="'+$address.websiteURL+'" id="instagram" target="_blank"><img src="'+$baseUrl+'assets/img/icons/web-link.png" alt="" class="social-img"></a>');
                }
                
                if($address.serviceDescription == ''){
                    $('#publicAddressServiceDescription').html("<p class='text-muted h4 font-weight-300'>Not Available</p>");
                } else {
                    $('#publicAddressServiceDescription').html("<p class='text-muted h4 font-weight-300'>"+$address.serviceDescription+"</p>");
                }

                if ($address.serviceURL != null) {
                    $arrServiceURL = $address.serviceURL.split(',');
                    $arrServiceURL.forEach(function(serviceURL) {
                        $arr = serviceURL.split('.');
                        if ($arr[$arr.length - 1] == 'pdf') {
                            $('#publicAddressServices').append('<div class="col-lg-3 p-2"><a href="' + $uploads + serviceURL + '" target="_blank"><img class="address-img" src="' + $baseUrl + 'assets/img/pdfPlaceholder.png"></a></div>');
                        } else if ($arr[$arr.length - 1] == 'doc' || $arr[$arr.length - 1] == 'docx') {
                            $('#publicAddressServices').append('<div class="col-lg-3 p-2"><a href="' + $uploads + serviceURL + '" target="_blank"><img class="address-img" src="' + $baseUrl + 'assets/img/docPlaceholder.png"></a></div>');
                        } else {
                            $('#publicAddressServices').append('<div class="col-lg-3 p-2"><img data-enlargable style="cursor: zoom-in" class="address-img dynamicImages" src="' + $uploads + serviceURL + '"></div>');
                        }
                    });
                } else{
                    $('#publicAddressServices').append('<p class="text-muted h4 font-weight-300">Not available</p>');
                }

                if($address.description == ''){
                    $('#publicAddressDescription').html('<p class="text-muted h4 font-weight-300">Not available</p>');
                } else {
                    $('#publicAddressDescription').html('<p class="text-muted h4 font-weight-300">'+$address.description+'</p>');
                }

                if ($weekDays[0] != undefined) {
                    $weekDays.forEach(function(day) {
                        if (day.isOpen == 1) {
                            $('#publicAddressWeekDays').append('<div class="time-table"><span>' + day.dayName + '</span><span>' + day.openTime.substring(0, 5) + '-' + day.closeTime.substring(0, 5) + '</span></div>');
                        } else {
                            $('#publicAddressWeekDays').append('<div class="time-table"><span>' + day.dayName + '</span><span>Closed</span></div>');
                        }
                    })
                } else {
                    $('#publicAddressWeekDays').append('<div class="time-table"><div><p class="text-muted h4 font-weight-300">Not available</p></div></div>');
                }

                if ($address.isDeliveryAvailable == 1) {
                    $('#publicAddressDeliveryStatus').html('<span class="text-muted">Delivery</span><span>Available <i class="ni ni-fat-remove ml-2 text-danger"></i></span>');
                } else {
                    $('#publicAddressDeliveryStatus').html('<span class="text-muted">Delivery</span><span>Not Available <i class="ni ni-check-bold ml-2 green"></i></span>');
                }

                if ($address.imageURL != null) {
                    $('#imageSlider').css('display','block');
                    $arrImageURL = $address.imageURL.split(',');
                    $arrImageURL.forEach(function(imageURL) {
                        $('.image-slider').slick('slickAdd','<div class="item"><div><img data-enlargable style="cursor: zoom-in"  src="' + $uploads + imageURL + '" class="img-fluid dynamicImages" onerror="' + $onErrorString + '"></div></div>');
                    });
                } else {
                    $('#imageSlider').css('display','none');
                    $('#publicAddressImages').append('<p class="text-muted h4 font-weight-300">Not available</p>');
                }

                $('#fullHeightModalRight').modal({backdrop: 'static', keyboard: false});
            }
        })
    }