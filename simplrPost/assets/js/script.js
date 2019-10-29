$url = 'http://test.code-apex.com/simplrPost/index.php/admin/admin/'
function showModalData(id){
    $.ajax({
        type: "post",
        url: $url+ 'getModalData',
        data: {
            'addressId': id
        },
        success: function(data) {
            data = JSON.parse(data);
            $('#publicAddressName').html(data.shortName)
        }
    })
}