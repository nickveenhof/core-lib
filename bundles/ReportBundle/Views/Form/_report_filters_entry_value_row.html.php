<div class="choice-wrapper col-xs-4">
    <div class="form-group">
        <label class="<?php echo $form->vars['label_attr']['class']; ?>" for="<?php echo $form->vars['id']; ?>"><?php echo $view['translator']->trans($form->vars['label']); ?></label>
        <input type="text" id="<?php echo $form->vars['id']; ?>" name="<?php echo $form->vars['full_name']; ?>" class="<?php echo $form->vars['attr']['class']; ?>" value="<?php echo $form->vars['data']; ?>" />
    </div>
</div>
