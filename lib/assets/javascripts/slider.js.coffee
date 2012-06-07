# Slider
#
# Copyright (c) 2011 TechnoGate <support@technogate.fr>
#
# Permission is hereby granted, free of charge, to any person obtaining
# a copy of this software and associated documentation files (the
# "Software"), to deal in the Software without restriction, including
# without limitation the rights to use, copy, modify, merge, publish,
# distribute, sublicense, and/or sell copies of the Software, and to
# permit persons to whom the Software is furnished to do so, subject to
# the following conditions:
#
# The above copyright notice and this permission notice shall be
# included in all copies or substantial portions of the Software.
#
# THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
# EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
# MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
# NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
# LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
# OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
# WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
#

# XXX: Add docs

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
    @step        = @options.step or 1

    @tray.addClass 'slider_tray'
    @create_arrows() if @options.controls
    @tray.wrap "<div class='slider_window' />"
    @window = @tray.parent()
    @set_css()
    @slided = 0

  left_arrow:  -> @slider.find('.left_arrow').first()
  right_arrow: -> @slider.find('.right_arrow').first()

  arrow_html: (direction) ->
    "<a href='#'class='#{direction}_arrow'></a>"

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
      display:      'block'

    @right_arrow().css
      'margin-top': "#{@item_height / 2}px"
      float:        'left'
      display:      'block'

  calculate_left_value: (direction, value) ->
    value = parseInt(value) or 0
    if direction == 'left'
      @slided -= @step
      value + (@step * @item_width)
    else
      @slided += @step
      value - (@step * @item_width)

  handle_arrow_event: (direction) ->
    @tray.css 'left', (@calculate_left_value direction, (@tray.css 'left'))

    if @slided <= 0
      @left_arrow().addClass 'disabled'
      @right_arrow().removeClass 'disabled'
    else if @slided >= @item_count - @display
      @right_arrow().addClass 'disabled'
      @left_arrow().removeClass 'disabled'
    else
      @left_arrow().removeClass 'disabled'
      @right_arrow().removeClass 'disabled'
