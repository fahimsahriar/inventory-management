$(document).ready(function() {
    let c = 0;
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
        });
    }
    const updateMaxQuantity = (productDropdown) => {
        const product_id = productDropdown.val();
        const baseUrl = window.location.protocol + "//" + window.location.host + "/updated_cms"

        // AJAX request
        $.ajax({
            url: `${baseUrl}/invoices/get_quantity/${product_id}`,
            type: "get",
            success: function(response) {
                productDropdown.closest('.form-group').find('.product_quantity').attr('max', response);
                updateSubmitButton();
            },
        });
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
            console.log("Here goes wrong");
            formGroup.find('.quantity_warning').show();
            return false;
        } else {
            console.log("Here goes right");
            formGroup.find('.quantity_warning').hide();
            return true;
        }
    };

    const updateSubmitButton = () => {
        // Iterate over each input with the class .product_quantity
        let iii = 0;
        let valid = true;
        $('.product_quantity').each(function() {
            if(iii==0){
                iii++;
            }else{
                let value = $(this).val();
                let maxvalue = $(this).attr('max');

                if(value == 0 ){
                    valid = false;
                    return;
                }
                if(parseInt(value)>parseInt(maxvalue)){
                    valid = false;
                    console.log("making it wrong");
                }
            }
        });
        let index_tracker = 0;
        $('.product_id').each(function(i, obj) {
            if(index_tracker==0){
                index_tracker++;
            }else{
                if($(this).val() === ''){
                    console.log("wrong");
                    valid = false;
                }
            }
        });
        $('#submit').prop('disabled', !valid);
    }

    // when 'Add More Products' button is clicked
    $("#add_more_products").click(function() {
        console.log("adding more product");
        updateSubmitButton(); // Update on page load
        var $template = $("#product-template").clone().removeAttr('id').show(); // Clone the template and remove its id
        $("#product-section").append($template); // Append the cloned template to the product section

        refreshProductOptions(); // refresh the product options
    });
    $('#product-section').on('click', '.remove-product', function() {
        $(this).parent().remove(); // Remove the parent product section of the clicked remove button
        refreshProductOptions(); // Call to refresh product options, to ensure consistency
    });

    updateSubmitButton(); // Update on page load
    // Call refreshProductOptions when the product changes
    $(document).on("change", ".product_id", function() {
        updateMaxQuantity($(this));
        refreshProductOptions();
        updateSubmitButton(); // Update on page load
    });

    // when quantity is changed
    $(document).on("change", ".product_quantity", function() {
        checkQuantity($(this));
        updateSubmitButton(); // Update on page load    
    });

});