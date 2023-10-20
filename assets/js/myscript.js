// Delete Category Record With Model
function loadCategoryModal(id, name) {
    $('#modal-name').html(name);
    $('#modal-confirm_delete').attr('onclick', `confirmCategoryDelete(${id})`);
    $('#deletecategory').modal('show');
}
// Delete Category Confirm Ajax Code
function confirmCategoryDelete(id) {
    var url = '{{ path('category.delete', {'id': 'PLACEHOLDER'}) }}';
    url = url.replace('PLACEHOLDER', id);
    var redirecturl = '{{ path('category.list') }}';
    $.ajax({
        type: 'POST',
        url: url,
        success: function(response) {
            if (response.success == true) {
                var flashMessage = response.flash_message;
                var flashContainer = $('<div class="container-fluid"><div class="row"><div class="col-md-12"><div class="alert alert-success mt-2 message">' + flashMessage + '</div></div></div></div>');
                $('body').append(flashContainer);
                $('#deletecategory').modal('hide');
                location.href = redirecturl;
            }else if(response.success == false){
                var flashMessage = response.flash_message;
                var flashContainer = $('<div class="container-fluid"><div class="row"><div class="col-md-12"><div class="alert alert-danger mt-2 message">' + flashMessage + '</div></div></div></div>');
                $('body').append(flashContainer);
                $('#deletecategory').modal('hide');
                location.href = redirecturl;
            }
        },
        error: function(error) {
            // Handle error response
        }
    });
}

// Delete Product Record With Model
function loadProductModal(id, name) {
    $('#modal-name').html(name);
    $('#modal-confirm_delete').attr('onclick', `confirmProductDelete(${id})`);
    $('#deleteproduct').modal('show');
}

// Delete Product Confirm Ajax Code
function confirmProductDelete(id) {
    var url = '{{ path('product.delete', {'id': 'PLACEHOLDER'}) }}';
    url = url.replace('PLACEHOLDER', id);
    var redirecturl = '{{ path('product.list') }}';
    $.ajax({
        type: 'POST',
        url: url,
        success: function(response) {
            if (response.success == true) {
                var flashMessage = response.flash_message;
                var flashContainer = $('<div class="container-fluid"><div class="row"><div class="col-md-12"><div class="alert alert-success mt-2 message">' + flashMessage + '</div></div></div></div>');
                $('body').append(flashContainer);
                $('#deleteproduct').modal('hide');
                location.href = redirecturl;
                }
        },
        error: function(error) {
            // Handle error response
        }
    });
}

// toast message hide after few second
$(document).ready(function(){
    setTimeout(function() {
        $(".message").hide('blind', {}, 300)
    }, 3000);
});