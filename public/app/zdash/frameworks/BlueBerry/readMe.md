# Atributes and meaning

## Class values
- text-field            : Defines a text field input, applied on input of type text
- disabled              : Declares an input disabled
- singleLine            : Declares a an input wrapper a single type (a row), to be applied on input parent  element
- labeled-input.pos-x   : Defines a labeled input, having its label on the side
- labeled-input.fluid   : Defines a labeled input, having its label on the top with full width
- input-info            : Declares an input info wrapper, this element should be placed within the same input wrapper, but comes after the input element. 
- text-field.readonly   : Declares a text input field that is readonly, should be applied on the input element
- family-input          : Declares a parent for xfield element
- xfield                : Declares an element a child of family-input, which could hold any input wrapper [family-input > xfield > iwrapper]
- twins-input           : Declares an iwrapper a twin wrapper for holding twin input (side by side input). all input must be wrapped with a div
- xfield.fixed          : Declares an xfield width to be unaffected by the wrap mode of family-input
- iwrapper              : Declares a div element, the wrapper of an input element(s)
- lgd-left              : Sets the location of twins input wrapper ledgend to the left. It is applied to twins-input element
- lgd-right             : Sets the location of twins input wrapper ledgend to the right. It is applied to twins-input element
- legend-border         : creates a legend that has only top, left and right border located along the top border of the wrapper. It is applied to the iwrapper element


    **Note**
    - If wrapper is declared singleLine, then input-info will overflow without obstructing neighbouring element, as wrapper will maintained single line height.
    - If wrapper is not declared singleLine, then input-info will disrrupt neighbouring element, as wrapper will be altered, taking the available height plus the height of the input-info element.

## Data 
- data-wrapViewPort     : Defines the view port at which siblings get wrapped. its is applied to .family-input element and .labeled-input
- data-active-color     : Defines the border color for input element and the input icon when active (has focus). It is supplied to the input element wrapper (.iwrapper). Value = "activeColorValue, normalBorderColor". e.g data-active-color = "blue, #ccc" 
- data-placeholder      : Defines the place holder for the input element. It is supplied to the input element wrapper (.iwrapper). Value = "PlaceholderText", e.g data-placeholder = "Email". its value is copied to legend on focus/active state, if the data-legend-value attribute is not present
- data-state            : Defines the default state of an input element. It is supplied to the input element wrapper (.iwrapper). Value = "active | inactive"
- data-lengend-value    : if this attribute is present, its value is used as the legend (active/focus state) value. It is supplied to the input element wrapper element