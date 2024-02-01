$(document).ready(function() {
    // Disable options in all other dropdowns that are currently selected
    const refreshProductOptions = function() {
        // Enable all options first
        $(".product_id option").prop('disabled', false);

        // Get values of all selected products except for the current one
        $(".product_id").each(function() {
            const currentDropdown = $(this);
            const selectedValue = currentDropdown.val();

            if (selectedValue != "") {
                $(".product_id").not(currentDropdown).find('option[value=' + selectedValue + ']').prop('disabled', true);
            }

            // get the closest ancestor form-group 
            var formGroup = $(this).closest('.form-group');
            // then find the quantity within that form-group
            var quantityField = formGroup.find('.product_quantity');
            // get the value of the quantity field
            var quantity = quantityField.val();
            updateMaxQuantity($(this), quantity);
        });
        updateSubmitButton(); // Update on page load
    }
    const updateMaxQuantity = (productDropdown, quantity) => {
        const product_id = productDropdown.val();
        const baseUrl = window.location.protocol + "//" + window.location.host + "/fahim-onboarding"

        if(invoiceid){    
            let request1 = $.ajax({
                url: `${baseUrl}/invoices/get_quantity/${product_id}`,
                type: "get",
            });
        
            let request2 = $.ajax({
                url: `${baseUrl}/invoices/getInvoicedQuantity/${product_id}/${invoiceid}`,
                type: "get",
            });
            if(parseInt(product_id) && parseInt(invoiceid)){
                // AJAX request for editing invoice
                $.when(request1, request2).done(function(response1, response2) {
                    // response1 and response2 contain the responses from each ajax request
                    let stock = parseInt(response1[0]);
                    let quantity = parseInt(response2[0]);
    
                    productDropdown.closest('.form-group').find('.product_quantity').attr('max', parseInt(stock) + parseInt(quantity));
    
                }).fail(function(jqXHR, textStatus, errorThrown) {
                    // Handle or report the error here
                    console.log('An error occurred: ' + errorThrown);
                });
            }
        }else if(product_id){    
            // AJAX request for new invoice
            $.ajax({
                url: `${baseUrl}/invoices/get_quantity/${product_id}`,
                type: "get",
                success: function(response) {
                    productDropdown.closest('.form-group').find('.product_quantity').attr('max', response);
                    updateSubmitButton();
                },
            });
        }
    };

    let checkQuantity = (quantityInput) => {
        const quantity = parseInt(quantityInput.val());
        const maxQuantity = parseInt(quantityInput.attr('max'));

        // The form-group element that contains the given quantityInput
        const formGroup = quantityInput.closest('.form-group');

        if (isNaN(maxQuantity) || isNaN(quantity)) {
            formGroup.find('.quantity_warning').hide();
            return false;
        }

        if (quantity > maxQuantity) {
            formGroup.find('.quantity_warning').show();
            return false;
        } else {
            formGroup.find('.quantity_warning').hide();
            return true;
        }
    };

    const updateSubmitButton = () => {
        // Iterate over each input with the class .product_quantity
        let valid = true;
        let index_tracker2 = 0;
        let lengthofdivs = 0;
        console.log("Called");
        $('.product_quantity').each(function() {
            console.log(".product_quantity:", $(this));
            lengthofdivs++;

            if(index_tracker2==0){
                index_tracker2++;
            }else{
                let value = $(this).val();
                let maxvalue = $(this).attr('max');

                if(value == 0 || value == undefined || value == null || value == ""){
                    valid = false;
                }
                if(parseInt(value)>parseInt(maxvalue)){
                    valid = false;
                }
            }
        });
        if(lengthofdivs == 1){
            valid = false;
        }

        let index_tracker = 0;
        $('.product_id').each(function(i, obj) {
            console.log(".product_id:", $(this).val());
            if(index_tracker==0){
                index_tracker++;
            }else{
                if($(this).val() === '' || $(this).val() == null || $(this).val() == undefined){
                    valid = false;
                }
            }
        });
        $('#submit').prop('disabled', !valid);
    }
    refreshProductOptions();
    // when 'Add More Products' button is clicked
    $("#add_more_products").click(function() {
        //var $template = $("#product-template").clone().removeAttr('id').show(); // Clone the template and remove its id
        var $template = $("#product-template").clone().attr('id', 'product-section').show();
        $("#product-container").append($template); // Append the cloned template to the product section

        refreshProductOptions(); // refresh the product options
        updateSubmitButton(); // Update on page load
    });


    // when 'Remove product' button is clicked
    $(document).on('click', '.remove-product', function() {
        $(this).parent().remove(); // Remove the parent product section of the clicked remove button
        refreshProductOptions(); // Call to refresh product options, to ensure consistency
        updateSubmitButton(); 
    });

    updateSubmitButton(); // Update on page load
    // Call refreshProductOptions when the product changes
    $(document).on("change", ".product_id", function() {
        // get the closest ancestor form-group 
        var formGroup = $(this).closest('.form-group');
        // then find the quantity within that form-group
        var quantityField = formGroup.find('.product_quantity');
        // get the value of the quantity field
        var quantity = quantityField.val();
        updateMaxQuantity($(this), quantity);
        refreshProductOptions();
        updateSubmitButton(); // Update on page load
    });

    // when quantity is changed
    $(document).on("change", ".product_quantity", function() {
        checkQuantity($(this));
        updateSubmitButton(); // Update on page load    
    });

});