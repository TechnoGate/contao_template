#= require slider

describe 'Slider', ->
  beforeEach ->
    loadFixtures 'slider.html'
    @options =
      dom: '#slider'
      width: 770
      height: 120
      autoplay: true
      duration: 500
      controls: true
      display: 3
      step: 3

    @slider = new Slider @options

  it 'should correctly load the fixtures', ->
    (expect ($ '#slider')).toExist()

  it 'should throw an error if the dom does not exist', ->
    (expect (-> new Slider dom: 'not-exist')).toThrow \
      new Error 'The dom not-exist does not exist.'

  it 'should set the options', ->
    (expect @slider.options).toEqual @options

  it 'should set the item count', ->
    (expect @slider.item_count).toEqual 9

  it 'should be able to figure out the outerWidth', ->
    (expect @slider.item_width).toEqual 142

  it 'should add slider_tray to the UL', ->
    (expect ($ '#slider ul').hasClass 'slider_tray').toBeTruthy()

  it 'should wrap the UL with a div.window', ->
    (expect ($ '#slider ul.slider_tray').parent()).toBe('div.slider_window')

  it 'should create a left_arrow and right_arrow if controls is requested', ->
    (expect ($ '#slider > a.left_arrow')).toExist()
    (expect ($ '#slider > a.right_arrow')).toExist()

  it 'should hardcode the CSS for all elements', ->
    # window
    (expect ($ '#slider .slider_window').css 'width').toEqual '770px'
    (expect ($ '#slider .slider_window').css 'height').toEqual '120px'
    (expect ($ '#slider .slider_window').css 'overflow').toEqual 'hidden'
    (expect ($ '#slider .slider_window').css 'position').toEqual 'relative'
    (expect ($ '#slider .slider_window').css 'float').toEqual 'left'

    # ul
    (expect ($ '#slider ul.slider_tray').css 'width').toEqual '1278px'
    (expect ($ '#slider ul.slider_tray').css 'height').toEqual '120px'
    (expect ($ '#slider ul.slider_tray').css 'display').toEqual 'block'
    (expect ($ '#slider ul.slider_tray').css 'position').toEqual 'relative'
    (expect ($ '#slider ul.slider_tray').css 'overflow').toEqual 'hidden'
    (expect ($ '#slider ul.slider_tray').css 'list-style').toEqual ''
    (expect ($ '#slider ul.slider_tray').css 'margin').toEqual ''
    (expect ($ '#slider ul.slider_tray').css 'padding').toEqual ''

    # li
    (expect ($ '#slider ul.slider_tray > li').css 'position').toEqual 'relative'
    (expect ($ '#slider ul.slider_tray > li').css 'display').toEqual 'list-item'
    (expect ($ '#slider ul.slider_tray > li').css 'float').toEqual 'left'
    (expect ($ '#slider ul.slider_tray > li').css 'overflow').toEqual 'hidden'

    # buttons
    (expect ($ '#slider .left_arrow').css 'display').toEqual 'block'
    (expect ($ '#slider .left_arrow').css 'float').toEqual 'left'
    (expect ($ '#slider .left_arrow').css 'margin-top').toEqual '50px'
    (expect ($ '#slider .left_arrow').hasClass 'disabled').toBeTruthy()
    (expect ($ '#slider .right_arrow').css 'display').toEqual 'block'
    (expect ($ '#slider .right_arrow').css 'float').toEqual 'left'
    (expect ($ '#slider .right_arrow').css 'margin-top').toEqual '50px'

  describe 'With controls turned off', ->
    beforeEach ->
      @options.controls = false
      jasmine.getFixtures().cleanUp()
      loadFixtures 'slider.html'
      @slider = new Slider @options

    it 'should not create left_arrow and right_arrow if controls is off', ->
      options = @options
      options.controls = false
      new Slider options
      (expect ($ '#slider > .left_arrow')).not.toExist()
      (expect ($ '#slider > .right_arrow')).not.toExist()

  describe 'Control buttons or Arrows', ->
    it 'should bind a click event on the left arrow' , ->
      spyOn @slider, 'handle_arrow_event'
      ($ '#slider .left_arrow').removeClass 'disabled'
      ($ '#slider .left_arrow').click()

      (expect @slider.handle_arrow_event).toHaveBeenCalledWith 'left'

    it 'should bind a click event on the right arrow' , ->
      spyOn @slider, 'handle_arrow_event'
      ($ '#slider  .right_arrow').click()

      (expect @slider.handle_arrow_event).toHaveBeenCalledWith 'right'

    it 'should move everything to the right', ->
      ($ '#slider > .right_arrow').click()

      (expect ($ '#slider .slider_tray').css 'left').toEqual '-426px'

    it 'should move everything to the left', ->
      ($ '#slider .left_arrow').click()

      (expect ($ '#slider .slider_tray').css 'left').toEqual '426px'


    it 'should not do anything if the left button is disabled', ->
      spyOn @slider, 'handle_arrow_event'
      ($ '#slider .left_arrow').addClass 'disabled'
      ($ '#slider .left_arrow').click()

      (expect @slider.handle_arrow_event).not.toHaveBeenCalledWith 'left'
      (expect @slider.handle_arrow_event).not.toHaveBeenCalledWith 'right'

    it 'should not do anything if the button is disabled', ->
      spyOn @slider, 'handle_arrow_event'
      ($ '#slider .right_arrow').addClass 'disabled'
      ($ '#slider .right_arrow').click()

      (expect @slider.handle_arrow_event).not.toHaveBeenCalledWith 'right'

    it 'should enable both buttons if we can scroll more', ->
      ($ '#slider .right_arrow').click()

      (expect ($ '#slider .right_arrow').hasClass 'disabled').toBeFalsy()
      (expect ($ '#slider .left_arrow').hasClass 'disabled').toBeFalsy()

    it 'should disable the right button and enable the left one if the right was clicked', ->
      ($ '#slider .right_arrow').click()
      ($ '#slider .right_arrow').click()

      (expect ($ '#slider .right_arrow').hasClass 'disabled').toBeTruthy()
      (expect ($ '#slider .left_arrow').hasClass 'disabled').toBeFalsy()

    describe "with different display/step", ->
      beforeEach ->
        jasmine.getFixtures().cleanUp()
        loadFixtures 'slider.html'

      describe "display: 1, step: 1", ->
        beforeEach ->
          @options.display = 1
          @options.step = 1

          @slider = new Slider @options

        it 'should enable both buttons if we can scroll more', ->
          ($ '#slider .right_arrow').click()
          ($ '#slider .right_arrow').click()
          ($ '#slider .right_arrow').click()
          ($ '#slider .right_arrow').click()
          ($ '#slider .right_arrow').click()
          ($ '#slider .right_arrow').click()
          ($ '#slider .right_arrow').click()

          (expect ($ '#slider .right_arrow').hasClass 'disabled').toBeFalsy()
          (expect ($ '#slider .left_arrow').hasClass 'disabled').toBeFalsy()

        it 'should disable the right button and enable the left one if the right was clicked', ->
          ($ '#slider .right_arrow').click()
          ($ '#slider .right_arrow').click()
          ($ '#slider .right_arrow').click()
          ($ '#slider .right_arrow').click()
          ($ '#slider .right_arrow').click()
          ($ '#slider .right_arrow').click()
          ($ '#slider .right_arrow').click()
          ($ '#slider .right_arrow').click()

          (expect ($ '#slider .right_arrow').hasClass 'disabled').toBeTruthy()
          (expect ($ '#slider .left_arrow').hasClass 'disabled').toBeFalsy()

      describe "display: 2, step: 1", ->
        beforeEach ->
          @options.display = 2
          @options.step = 1

          @slider = new Slider @options

        it 'should enable both buttons if we can scroll more', ->
          ($ '#slider .right_arrow').click()
          ($ '#slider .right_arrow').click()
          ($ '#slider .right_arrow').click()
          ($ '#slider .right_arrow').click()
          ($ '#slider .right_arrow').click()
          ($ '#slider .right_arrow').click()

          (expect ($ '#slider .right_arrow').hasClass 'disabled').toBeFalsy()
          (expect ($ '#slider .left_arrow').hasClass 'disabled').toBeFalsy()

        it 'should disable the right button and enable the left one if the right was clicked', ->
          ($ '#slider .right_arrow').click()
          ($ '#slider .right_arrow').click()
          ($ '#slider .right_arrow').click()
          ($ '#slider .right_arrow').click()
          ($ '#slider .right_arrow').click()
          ($ '#slider .right_arrow').click()
          ($ '#slider .right_arrow').click()

          (expect ($ '#slider .right_arrow').hasClass 'disabled').toBeTruthy()
          (expect ($ '#slider .left_arrow').hasClass 'disabled').toBeFalsy()

  describe "Step", ->
    beforeEach ->
      jasmine.getFixtures().cleanUp()
      loadFixtures 'slider.html'
      @options.step = null
      @slider = new Slider @options

    it 'should defaults to 1', ->
      (expect @slider.step).toEqual 1

    it 'should move step by step', ->
      ($ '#slider > .right_arrow').click()

      (expect ($ '#slider .slider_tray').css 'left').toEqual '-142px'
