var PlainGallery = (function() {
    
    var module = {},
    
    // Private
    // ---------------------------------------------------------------------

        // Variables
        // ---------------------------------------------------------------------
                
        
        // Handlers
        // ---------------------------------------------------------------------
        
       
    	_handleAlbumClick = function() {
    		jQuery("#gallery-" + jQuery(this).data('gallery-id')).show();
    		jQuery(".album").hide();
    		jQuery(".albums button").css('display','block');
	    },
	    
	    _handleBackButtonClick = function() {
	    	jQuery(".gallery").hide();
	    	jQuery(".album").show();
	    	jQuery(".albums button").css('display','none');
	    },
	    
        
        
        // Helper functions
        // ---------------------------------------------------------------------
        
        
        
	    _bindUIActions = function() {
	    	jQuery('.album').on( "click", _handleAlbumClick );
	    	jQuery('.albums button').on( "click", _handleBackButtonClick );
	    };
    
    
    // Public
    // ---------------------------------------------------------------------
    
    module.init = function(config) {
        _bindUIActions();
    };
    
    return module;
})();