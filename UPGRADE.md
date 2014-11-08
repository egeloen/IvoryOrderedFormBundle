# UPGRADE

### 1.1 to 2.0

 * All protected properties and methods have been updated to private except for entry points. This is mostly motivated
   for enforcing the encapsulation and easing backward compatibility. The same goes for the library.

### 1.0 to 1.1

The `ivory_ordered_form.form_orderer_factory` service and `ivory_ordered_form.form_orderer_factory.class` parameter has
been removed according to the changes done in the library. It has been replaced by the `ivory_ordered_form.form_orderer`
service and `ivory_ordered_form.form_orderer.class` parameter.
