# This class provides some helpfull methods to hide the default text
# on view and show the text on blue if it is equal to the default text
#
# Usage:
#     contact_form_dv = new FormDefaultValues('#contact_form')
#
#     contact_form_dv.run()
#
# Wael Nasreddine <wael.nasreddine@gmail.com>
window.FormDefaultValues = class FormDefaultValues
  # Constructor
  # @param [String] The dom of the parent node of all inputs and text areas
  constructor: (@domId) ->
    @default_values = {}

  # Run binds some methods to watch the inputs of type text
  # and textareas
  run: ->
    ($ @domId).find('input[type="text"], textarea').each (i, el) =>
      @watch_element(el)

  # Watch element
  #
  # @param [String] The element
  watch_element: (el) =>
    name = ($ el).attr('name')
    # Find the default value either as the inputs val or a label
    default_value = ($ el).val()
    unless default_value
      id = ($ el).attr('id')
      default_value = ($ el).parentsUntil('form').parent().find("label[for='#{id}']").html()

    # Set the initial value of the input
    ($ el).val(default_value)

    # Store the default value in a instance variable
    @default_values[name] = default_value

    # Bind the focus event
    ($ el).focus =>
      if ($ el).val() == @default_values[name]
        ($ el).val('')

    # Bind the blur event
    ($ el).blur =>
      if ($ el).val() == ''
        ($ el).val(@default_values[name])
