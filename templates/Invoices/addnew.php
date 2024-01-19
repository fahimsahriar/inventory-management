<h1>Select a product and quantity</h1>

<div id="product_fields_container">
    <div class="product_fields">
        <?= $this->Form->create() ?>
            <!-- Product Dropdown -->
            <?= $this->Form->control('product_id', ['options' => $products, 'empty' => 'Select a product', 'class'  => 'product_id']) ?>
            <!-- Quantity -->
            <?= $this->Form->number('quantity', ['min' => 0, 'class' => 'product_quantity']) ?> 
            <!-- Warning for excessive quantity -->
            <div class="quantity_warning" style="display:none;color:red;">The quantity entered is greater than available stock.</div>
        <?= $this->Form->end() ?>
    </div>
</div>

<button id="add_more_products">Add More Products</button>

<script>
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
            });
        }
        const updateMaxQuantity = (productDropdown) => {
            const product_id = productDropdown.val();

            // AJAX request
            $.ajax({
                url: "<?= $this->Url->build(['_name' => 'get_quantity', 'id' => '']); ?>" + product_id,
                type: "get",
                success: function(response) {
                    productDropdown.closest('.product_fields').find('.product_quantity').data('max', response);
                },
            });
        };

        const checkQuantity = (quantityInput) => {
           // Get the max quantity for this specific product
           const max_quantity = quantityInput.data('max');
           const quantity = quantityInput.val();

            if (parseInt(quantity) > max_quantity) {
                quantityInput.siblings('.quantity_warning').show();
            } else {
                quantityInput.siblings('.quantity_warning').hide();
            }
        };

                // Call refreshProductOptions when the product changes
        $(document).on("change", ".product_id", function() {
            updateMaxQuantity($(this));
            refreshProductOptions();
        });
        
        // when 'Add More Products' button is clicked
        $("#add_more_products").click(function() {
            const new_product_fields = $(".product_fields:first").clone();

            new_product_fields.find(".product_id, .product_quantity").val('');
            new_product_fields.find(".quantity_warning").hide();

            $("#product_fields_container").append(new_product_fields);

            refreshProductOptions(); // refresh the product options
        });

        // when quantity is changed
        $(document).on("change", ".product_quantity", function() {
            checkQuantity($(this));
        });

        // Trigger change event for the first product dropdown to populate the max quantity
        $(".product_id:first").trigger("change");
    });
</script>