window.Slider = class Slider
  constructor: (@options) ->
    if ($ @options.dom).length <= 0
      throw new Error "The dom #{@options.dom} does not exist."

    @slider      = ($ @options.dom)
    @tray        = @slider.find('ul').first()
    @item_count  = @tray.find('li').length
    @item_width  = @tray.find('li').first().outerWidth(true)
    @item_height = @tray.find('li').first().outerHeight(true)
    @items       = @tray.find('li')
    @display     = @options.display or 1

    @tray.addClass 'slider_tray'
    @create_arrows() if @options.controls
    @tray.wrap "<div class='slider_window' />"
    @window = @tray.parent()
    @set_css()

  left_arrow:  -> @slider.find('.left_arrow').first()
  right_arrow: -> @slider.find('.right_arrow').first()

  arrow_html: (direction) ->
    "<div class='#{direction}_arrow' style='cursor: pointer;'></div>"

  wrapper_html: ->

  create_arrows: ->
    @tray.before @arrow_html 'left'
    @tray.after  @arrow_html 'right'

    @left_arrow().bind 'click', (event) =>
      event.preventDefault()
      @handle_arrow_event 'left' if !@left_arrow().hasClass 'disabled'

    @right_arrow().bind 'click', (event) =>
      event.preventDefault()
      @handle_arrow_event 'right' if !@right_arrow().hasClass 'disabled'

  set_css: ->
    @window.css
      width:        "#{@options.width}px"
      height:       "#{@options.height}px"
      position:     'relative'
      overflow:     'hidden'
      float:        'left'

    @tray.css
      width:        "#{@item_width * @item_count}px"
      height:       "#{@options.height}px"
      display:      'block'
      position:     'relative'
      overflow:     'hidden'
      'list-style': 'none'
      margin:       0
      padding:      0

    @items.css
      position: 'relative'
      display:  'list-item'
      float:    'left'
      overflow: 'hidden'

    @left_arrow().addClass 'disabled'
    @left_arrow().css
      'margin-top': "#{@item_height / 2}px"
      float:        'left'

    @right_arrow().css
      'margin-top': "#{@item_height / 2}px"
      float:        'left'

  calculate_left_value: (direction, value) ->
    value = parseInt(value) or 0
    if direction == 'left'
      value - (@options.step * @item_width)
    else
      value + (@options.step * @item_width)

  handle_arrow_event: (direction) ->
    @tray.css 'left', (@calculate_left_value direction, (@tray.css 'left'))
