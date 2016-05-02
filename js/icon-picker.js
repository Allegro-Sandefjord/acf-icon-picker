// Get our version on autocomplete and put it in another object key
jQuery.fn.algolia_autocomplete = jQuery.fn.autocomplete;

(function ($){

// Define global vars
var client, index, is_widget;

// Support for ACF Widgets
$(document).on('widget-updated widget-added', function(e, widget){
    
    // Wait 500 ms
    setTimeout(function(){
    
        // Reinit Algolia-fields inside saved widget
        $(widget).find('.algolia-outer').initACFIconPicker();
        
    }, 500);
    
});

$(document).ready(function(){
    
    // Check if algoliasearch is loaded
    if( typeof algoliasearch !== 'undefined' )
    {
        // Init Algolia
        client = algoliasearch('KYYL714XXY', '7a86a04547fb3653f7ca1ae779f4f6e7');
        index = client.initIndex('icons');
    
        // Check if is widget-view
        is_widget = ($('body').hasClass('widgets-php') && $('#widgets-right').length);
        if(is_widget)
        {
            // Init widget when collapsing a widget
            $('.widget-top').click(function(){
                // Wait 500 ms
                setTimeout(function(){
                    
                    // Init all visible searchfields
                    $().initACFIconPicker();
                    
                }, 500);
            });
        }
    
        // Init all visible searchfields
        $().initACFIconPicker();
    }
    
    // Check if options form is present in DOM
    var form = $('form[name="acf-v4-acf-ip-optionspage"]');
    if(form.length)
    {
        // Toggle provider-list
        form.find('#acf_ip_prevent_inclusion_css').on('change', function(){
            if($(this).is(':checked'))
            {
                form.find('#acf_ip_custom_inclusion-tab').show();
            }
            else
            {
                form.find('#acf_ip_custom_inclusion-tab').hide();
            }
        })
    }
    
});

// Init
$.fn.initACFIconPicker = function()
{
    
    // Fetch algolia fields that is not initated
    var selector = $('.algolia-outer').not('.algolia-inited');
    
    // Loop through all algolia fields
    selector.each(function(i, el){
        
        // Get object
        var obj = $(el);
        
        // Skip if not visible
        if(!obj.is(':visible')) return;
        
        // Set as initiated
        obj.addClass('algolia-inited');
        
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
                
                // Add text before provider
                providers_array = $.map( providers_array, function( val, i ) {
                    return 'type:' + val;
                });
                
            }
            else
            {
                // Convert string to array, make facet-friendly
                var providers_array = ['type:' + providers];
            }
            
            // Check if we got providers
            if(providers_array.length > 0)
            {
                
                // Create facet-array
                var facets = {
                    facets: '*',
                    facetFilters: [
                        providers_array
                    ]
                };
            
                // Add to config-array
                algolia_config = $.extend(algolia_config, facets);
                
            }
            
        }
        
        // Find search-input
        obj.find('.acf-icon-autocomplete-me').algolia_autocomplete({ hint: false }, [{
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
                        
                        // WP Dashicons
                        case 'wp':
                            var provider_icon = $().generateIcon('wordpress', 'fa', 'provider', 'WP Dashicons');
                        break;
                        
                        // Pixeden 7 Stroke
                        case '7-stroke':
                            var provider_icon = $().generateIcon(ACFIP_var.pixeden_logo, 'img', 'provider-image', 'Pixeden 7 Stroke');
                        break;
                        
                        // Unknown provider
                        default:
                            var provider_icon = $().generateIcon('question', 'fa', 'provider', ACFIP_var.trans.unknown_provider);
                    }
                    
                    // Get highlighted text
                    var highlighted = suggestion._highlightResult.name.value;
                    
                    // Generate provider-markup
                    var provider_markup = '<div class="provider-outer">' + provider_icon + '</div>';
            
                    // Generate icon-markup
                    var icon_icon = $().generateIcon(suggestion.code, suggestion.type, false, suggestion.name);
                    var icon_markup = '<div class="suggest-icon-outer">' + icon_icon + '</div>';
            
                    // Generate suggestion-markup
                    var suggestion_markup = '<div class="suggestion-inner">' + highlighted + '</div>';
            
                    // Concatenate new markup
                    var markup = '<div class="suggestion-outer">' + provider_markup + icon_markup + suggestion_markup + '</div>';
            
                    // Edit label
                    suggestion._highlightResult.name.value = markup;
                    
                    // Return edited label
                    return suggestion._highlightResult.name.value;
                }
                
            }
            
        }]).on('autocomplete:selected', function(event, suggestion, dataset) {
    
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

    // Clear icon from icon-container
    $('.algolia-outer .clear-icon-js').click(function(e){
    
        // Prevent default event
        e.preventDefault();

        // Get object
        var obj = $(this);
    
        // Hide clear-btn
        obj.addClass('hidden');

        // Get parent
        var parent_el = obj.closest('.acf-input-wrap');

        // Empty hidden values
        parent_el.find('.input-acf-ip-type').val('');
        parent_el.find('.input-acf-ip-code').val('');
    
        // Hide provider-container
        parent_el.find('.provider a').addClass('hidden');

        // Generate markup
        var icon_markup = $().generateIcon('question', 'fa', 'empty-icon', ACFIP_var.trans.no_icon_selected);

        // Clear icon container
        parent_el.find('.icon-container-inner').html(icon_markup);

    });
};

// Generate markup for icon
$.fn.generateIcon = function(content, type, class_name, title)
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
            return '<i class="material-icons ' + class_name + '"' + (title ? ' title="' + title + '"' : '') + '>&#x' + content + ';</i>';
        break;
        
        // Font Awesome
        case 'fa':
            // Return markup
            return '<i class="fa fa-' + content + ' ' + class_name + '"' + (title ? ' title="' + title + '"' : '') + '></i>';
        break;
        
        // WordPress Dashicons
        case 'wp':
            // Return markup
            return '<span class="dashicons dashicons-' + content + ' ' + class_name + '"' + (title ? ' title="' + title + '"' : '') + '></span>';
        break;
        
        // 7 Stroke
        case '7-stroke':
            // Return markup
            return '<i class="stroke-icon pe-7s-' + content + ' ' + class_name + '"' + (title ? ' title="' + title + '"' : '') + '></i>';
        break;
        
        // Image
        case 'img':
            // Return markup
            return '<img class="' + class_name + '" src="' + content + '"' + (title ? ' title="' + title + '" alt="' + title + '"' : '') + '>'
        break;
    }
    
    // Unknown icontype
    return false;
};

})(jQuery);