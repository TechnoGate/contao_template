##############
# Slimbox2 startup
#

jQuery ->
  if not (/android|iphone|ipod|series60|symbian|windows ce|blackberry/i).test navigator.userAgent
    slimbox_options = {}
    jQuery ->
      ($ "a[rel^='lightbox']").slimbox slimbox_options, null, (el)->
        (@ == el) || ((@rel.length > 8) && (@.rel == el.rel))

#
#
##############