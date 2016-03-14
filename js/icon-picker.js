(function ($){

$(document).ready(function(){
   
    var client = algoliasearch('KYYL714XXY', '7a86a04547fb3653f7ca1ae779f4f6e7');
    var index = client.initIndex('icons');

    // Loop through all algolia fields
    $('.algolia-outer').each(function(){
        
        // Get object
        var obj = $(this);
        
        // Get hits per oage
        var per_page = obj.data('hits-per-page');
        
        // Make default config
        var algolia_config = {
            hitsPerPage: per_page
        };
        
        // Get facet-settings
        var use_facets = obj.data('use-facets');
        var providers = obj.data('active-provider');
        
        // Check if should use facets
        if(use_facets)
        {
            // Check if is comma in string
            if (providers.indexOf(',') >= 0)
            {
                // Split string into array
                var providers_array = providers.split(',');
            
                // Format to facet-friendly formatting
                providers_array = $.map( providers_array, function( val, i ) {
                    return 'type:' + val;
                });
            }
            else
            {
                // Convert string to array, make facet-friendly
                var providers_array = new Array('type:' + providers);
            }
            
            // Create facet-array
            var facets = {
                facets: 'type',
                facetFilters: providers_array
            };
            
            // Add to config-array
            algolia_config = $.extend(algolia_config, facets);
            
        }
        
        obj.find('.acf-icon-autocomplete-me').autocomplete({ hint: false }, [{
            source: function(q, cb) {
                index.search(q, algolia_config,
                function(error, content) {
                    if (error) {
                        cb([]);
                        return;
                    }
                    cb(content.hits, content);
                });
            },
            displayKey: 'name',
            templates: {
                suggestion: function(suggestion) {
            
                    // Check which type of icon
                    switch(suggestion.type)
                    {
                        // Google Material icons
                        case 'gmi':
                            var provider_icon = $().generateIcon('google', 'fa', 'provider', 'Google Material icons');
                        break;
                        // Font Awesome
                        case 'fa':
                            var provider_icon = $().generateIcon('flag', 'fa', 'provider', 'Font Awesome');
                        break;
                        default:
                            var provider_icon = $().generateIcon('question', 'fa', 'provider', 'Unknown provider');
                    }
            
                    // Generate markup
                    var icon_markup = $().generateIcon(suggestion.code, suggestion.type, false, suggestion.name);
            
                    // Generate markup
                    var provider_markup = '<div class="provider-outer">' + provider_icon + '</div>';
            
                    // Edit label
                    suggestion._highlightResult.name.value = provider_markup + '<div class="aa-suggestion-inner">' + icon_markup + suggestion._highlightResult.name.value + '</div>';
                    return suggestion._highlightResult.name.value;
                }
            }
        }]).on('autocomplete:selected', function(event, suggestion, dataset) {
    
            event.preventDefault();
    
            // Get target-field
            var input_field = $(event.target);

            // Get parent
            var parent_el = input_field.closest('.acf-input-wrap');

            // Set hidden values
            parent_el.find('.input-acf-ip-type').val(suggestion.type);
            parent_el.find('.input-acf-ip-code').val(suggestion.code);

            // Generate markup
            var icon_markup = $().generateIcon(suggestion.code, suggestion.type, false, suggestion.name);

            // Set icon
            parent_el.find('.icon-container-inner').html(icon_markup);
    
            // Display clear-btn
            parent_el.find('.clear-icon-js').removeClass('hidden');
    
            // Get provider-container
            var provider_container = parent_el.find('.provider');
    
            // Display current provider
            provider_container.find('[data-provider="' + suggestion.type + '"]').removeClass('hidden').siblings().addClass('hidden');

            // Empty search
            input_field.val('');

        });
    });

    // Reset form on blur
    $('.acf-icon-autocomplete-me').on('blur', function(e){
    
        // Clear field
        $(this).val('');
    
    });

    $('.algolia-outer .clear-icon-js').click(function(e){
    
        // Prevent default event
        e.preventDefault();

        // Get object
        var obj = $(this);
    
        // Hiden clear-btn
        obj.addClass('hidden');

        // Get parent
        var parent_el = obj.closest('.acf-input-wrap');

        // Empty hidden values
        parent_el.find('.input-acf-ip-type').val('');
        parent_el.find('.input-acf-ip-code').val('');
    
        // Hiden provider-container
        parent_el.find('.provider a').addClass('hidden');

        // Generate markup
        var icon_markup = $().generateIcon('question', 'fa', 'empty-icon');

        // Clear icon container
        parent_el.find('.icon-container-inner').html(icon_markup);

    });
    
});

// Generate markup for icon
$.fn.generateIcon = function(code, type, class_name, title)
{   
    // Check if class name is passed
    if(!class_name) class_name = '';
    
    // Check if class name is passed
    if(!title) title = '';
    
    // Check which type of icon
    switch(type)
    {
        // Google Material icons
        case 'gmi':
            // Return markup
            return '<i class="material-icons ' + class_name + '"' + (title ? ' title="' + title + '"' : '') + '>&#x' + code + ';</i>';
        break;
        // Font Awesome
        case 'fa':
            // Return merkup
            return '<i class="fa fa-' + code + ' ' + class_name + '"' + (title ? ' title="' + title + '"' : '') + '></i>';
        break;
    }
    // Unknown icontype
    return false;
};

})(jQuery);