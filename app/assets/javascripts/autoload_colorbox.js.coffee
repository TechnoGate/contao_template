jQuery ($) ->
  if ($ "a[rel^='lightbox']").length > 0
    ($ "a[rel^='lightbox']").colorbox()
  if ($ "a[rel^='colorbox']").length > 0
    ($ "a[rel^='colorbox']").colorbox()
