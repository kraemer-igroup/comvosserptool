/**
 *
 *  comvos - searchResultSimulator - copyright by comvos online medien GmbH   http://www.comvos.de
 *  
 *  version 1.0.0
 **/
(function($){
    $.fn.searchResultSimulator = function( method ){
            
        var methods = {
            destroy: function(){
          
            },
        
            init : function( options ) {
                var config = {
                    cssFile: '/typo3conf/ext/comvosserptool/Resources/css/searchresultsimulator.css',
                    previewTemplate: '<div class="srs-preview"><h3><a class="srs-title" href="#">Kein Titel</a></h3><div class="srs-summary"><div class="srs-url"><a href="#">http://www.domain.tld/path/to/url/</a></div><div class="srs-description"><span class="srs-date"/></div></div></div>',
                    previewSelector: '.srs-description',
                    maxLength: 156,
                    overlengthIndicator: '...',
                    allwaysVisible: true,
                    emptyValue: 'No description!',
                    fallbackFieldSelector: '',
                    overrideWithSelector: '',
                    prefix: '',
                    postfix: ''
                };
                
                if( options ){
                    $.extend( config , options );
                }
                config.previewTemplate='<div class="srs-container"><div class="srs-left">'+config.previewTemplate+'</div></div>';
                if(!jQuery('body').attr('data-srs-cssloaded')){
                    $('head').append('<link rel="stylesheet" href="'+config.cssFile+'" type="text/css" />');
                    jQuery('body').attr('data-srs-cssloaded',true)
                }
            
                var $field = $(this), $countTarget = $('<span class="srs-count"></span>'),
                $wizcontainer=$('.wizpos[data-input-id='+this.id+']');
                
                $countTarget.attr( 'data-input-id', $field.attr('id'));
            
                if(config.previewTemplate){
                    var $preview = $(config.previewTemplate);
                    if(config.allwaysVisible){
                        $preview.addClass('srs-allways-visible').appendTo($field.parents('div').first());
                    }else{
                        $preview.appendTo('body');
                    }
                    $preview.attr('data-input-id',$field.attr('id'));
                }
                if($wizcontainer.length){
                    $wizcontainer.append($countTarget);
                }else{
                    $field.after($countTarget);
                }
//                $field.parents('td').append($countTarget);
                
                var trimWord=function(untrimmedString,maxLength){
                    if( untrimmedString.length < maxLength){
                        return untrimmedString;
                    }
                    if( untrimmedString.substr( maxLength,1 ) == ' '){
                        return untrimmedString.substr( 0, maxLength );
                    }else{
                        var trimmedString = untrimmedString.substr(0, maxLength);
                        return trimmedString.substr(0, Math.min(trimmedString.length, trimmedString.lastIndexOf(" ")));
                    }
                };
                
                var updateSerpView = function(){
                
                    
                    var previewText = $field.val(),originalText=$field.val(),
                        $prev = jQuery(config.previewSelector), overrideText='';
                    if(config.overrideWithSelector.length){
                        overrideText = $(config.overrideWithSelector).val();
                    }
                    if(overrideText.length){
                        previewText = overrideText;
                        originalText = previewText;
                    }
                    if(previewText.length == 0 && config.fallbackFieldSelector.length){
                        previewText = jQuery(config.fallbackFieldSelector).val();
                        originalText = jQuery(config.fallbackFieldSelector).val();
                    }
                    
                    if(previewText.length && config.prefix.length){
                        previewText = config.prefix + previewText;
                    }
                    
                    if(previewText.length && config.postfix.length){
                        previewText += config.postfix;
                    }
                    
                    var countText = previewText.length, explanation='';
                    if(config.prefix.length){
                        explanation += config.prefix+' ['+config.prefix.length+'] ';
                    }
                    if(config.prefix.length || config.postfix.length){
                        explanation += originalText+' ['+originalText.length+']';
                    }
                    if(config.postfix.length){
                        explanation += ' '+ config.postfix+' ['+config.postfix.length+']';
                    }
                    if(explanation.length){
                        explanation = ' (' + explanation + ')';
                    }
                    
                    $countTarget.text( countText + explanation);
                    
                    if(config.maxLength){
                        
                        if(previewText.length > config.maxLength){
                            $countTarget.addClass('srs-overlength');
                            previewText = trimWord(previewText,config.maxLength);
                            previewText += config.overlengthIndicator;
                        }else{
                            $countTarget.removeClass('srs-overlength');
                        }
                    
                    }
                    
                    if(previewText.length == 0){
                        previewText=config.emptyValue;
                        $prev.addClass('srs-empty-value');
                    }else{
                        $prev.removeClass('srs-empty-value');
                    }
                
                    $prev.text(previewText);
                    if(!config.allwaysVisible){
                        $prev = $prev.parents('.srs-container');
                        $prev.css({
                            top: ($field.offset().top+$field.outerHeight())+'px',
                            left: $field.offset().left+'px'
                        });
                        $prev.show();
                    }
                };
                
                $('body').on('comvosserptoolchange',function(){
                   updateSerpView();
                });
                $field.keyup(function(){
                    updateSerpView();
                    $('body').trigger('comvosserptoolchange');
                }).trigger('keyup');
                
                
                $(window).load(function() {
                	updateSerpView();

                    $('div.form-control-clearable button.close').click(function(){
                        updateSerpView();
                        $('body').trigger('comvosserptoolchange');
                    });
                });
            }//init end
        
        
        };// end methods
        //
        // Method calling logic
        if ( methods[method] ) {
            
            return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ));
          
        } else if ( typeof method === 'object' || ! method ) {
            
            return methods.init.apply( this, arguments );
          
        } else {
            
            $.error( 'Method ' +  method + ' does not exist on jQuery.searchResultSimulator' );
          
        } 
      
    }
  
})( jQuery );