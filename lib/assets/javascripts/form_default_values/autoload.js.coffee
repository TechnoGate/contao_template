jQuery ($) ->
  ($ '.has_inputs_with_default_values').each (i, el) ->
    (new FormDefaultValues(el)).run()
