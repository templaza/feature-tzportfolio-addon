jQuery( document ).ready( function( $ ) {
    var feature_addon_onprepare = function ($this, $copy = false) {
        var $id         =   $this.data('group').replace('tzportfolio_addon_features', '');
        var $text       =   'Item '+$id;
        if ($this.find('#jform_addon__feature__tzportfolio_addon_features__tzportfolio_addon_features'+$id+'__title').val()) {
            $text       =   $this.find('#jform_addon__feature__tzportfolio_addon_features__tzportfolio_addon_features'+$id+'__title').val();
        } else if ($this.find('#jform_addon__feature__tzportfolio_addon_features__tzportfolio_addon_features'+$id+'__feature_type').val() === 'divider') {
            $text       =   'Divider '+$id;
        }
        var $container  =   $("<div class=\"tz-addon-accordion-container\"></div>");
        var $title      =   $('<h5 class="tz-addon-accordion-title"><a href="#">'+$text+'</a></h5>');
        $this.find('>.btn-toolbar').after($container);
        $this.find('>.btn-toolbar').after($title);

        $this.find('>.control-group').each(function (i, el){
            if ($copy && !$this.prev().hasClass('subform-repeatable-template-section')) {
                var $item_el    =   $(this).find('>.controls [name^="jform\[addon\]\[feature\]\[tzportfolio_addon_features\]\['+$this.data('group')+'\]"]');
                if ($item_el.attr('id')) {
                    var $name   =   $item_el.attr('id').replace('jform_addon__feature__tzportfolio_addon_features__'+$this.data('group')+'__','');
                    $item_el.val($this.prev().find('[name="jform\[addon\]\[feature\]\[tzportfolio_addon_features\]\['+$this.prev().data('group')+'\]\['+$name+'\]"]').val());
                    if( $item_el.prop('type') == 'select-one' ) {
                        $item_el.trigger("liszt:updated").trigger('change');
                    }

                    if ($item_el.hasClass('typoData')) {
                        var font_el =   $(this).find('.tzfont-container');
                        var values  =   $.parseJSON($item_el.val());
                        var base_id =   $item_el.data('id');
                        font_el.find('.' + base_id + '_fontfamily').val(values.fontFamily).trigger("liszt:updated").trigger('change');
                        font_el.find('.' + base_id + '_fontweight').val(values.fontWeight).trigger("liszt:updated").trigger('change');
                        font_el.find('.' + base_id + '_fontsize').val(values.fontSize);
                        font_el.find('.' + base_id + '_lineheight').val(values.lineHeight);
                        font_el.find('.' + base_id + '_fontstyle').val(values.fontStyle).trigger("liszt:updated").trigger('change');
                        font_el.find('.' + base_id + '_letterspacing').val(values.letterSpacing).trigger("liszt:updated").trigger('change');
                        font_el.find('.' + base_id + '_text_transform').val(values.textTransform).trigger("liszt:updated").trigger('change');
                        font_el.find('.' + base_id + '_text_decoration').val(values.textDecoration).trigger("liszt:updated").trigger('change');
                    }

                    if ($item_el.hasClass('pm-data')) {
                        var box_el  =   $(this).find('.tzportfolio-box-responsive');
                        var values  =   $.parseJSON($item_el.val());
                        box_el.find('.tzportfolio-box').each(function (i, group) {
                            $(group).find('input').each(function (i, pmbox){
                                console.log(values.md.top);
                                if ($(pmbox).parent().parent().hasClass('md')) {
                                    if ($(pmbox).hasClass('pm-top')) {
                                        $(pmbox).val(values.md.top);
                                    }
                                    else if ($(pmbox).hasClass('pm-right')) {
                                        $(pmbox).val(values.md.right);
                                    }
                                    else if ($(pmbox).hasClass('pm-bottom')) {
                                        $(pmbox).val(values.md.bottom);
                                    } else {
                                        $(pmbox).val(values.md.left);
                                    }
                                }
                                if ($(pmbox).parent().parent().hasClass('sm')) {
                                    if ($(pmbox).hasClass('pm-top')) {
                                        $(pmbox).val(values.sm.top);
                                    }
                                    else if ($(pmbox).hasClass('pm-right')) {
                                        $(pmbox).val(values.sm.right);
                                    }
                                    else if ($(pmbox).hasClass('pm-bottom')) {
                                        $(pmbox).val(values.sm.bottom);
                                    } else {
                                        $(pmbox).val(values.sm.left);
                                    }
                                }
                                if ($(pmbox).parent().parent().hasClass('xs')) {
                                    if ($(pmbox).hasClass('pm-top')) {
                                        $(pmbox).val(values.xs.top);
                                    }
                                    else if ($(pmbox).hasClass('pm-right')) {
                                        $(pmbox).val(values.xs.right);
                                    }
                                    else if ($(pmbox).hasClass('pm-bottom')) {
                                        $(pmbox).val(values.xs.bottom);
                                    } else {
                                        $(pmbox).val(values.xs.left);
                                    }
                                }
                            });

                        });
                    }

                }
            }
            $(this).detach().appendTo($container);
        });
        $title.on('click', function (e){
            e.preventDefault();
            if ($this.hasClass('active')) {
                $this.removeClass('active');
            } else {
                $this.addClass('active');
            }
        });
    }
    $('[data-base-name="tzportfolio_addon_features"]').each(function (i, el) {
        feature_addon_onprepare($(el));
    });
    $(document).on('subform-row-add', function(event, row){
        if ($(row).data('base-name')==='tzportfolio_addon_features') {
            feature_addon_onprepare($(row),true);
        }
    });
});