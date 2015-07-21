<form novalidate>
    <div class="box error">
        Error info lorem ipsum
    </div>

    <label for="new-password" class="label">
        <span class="txt">{{ language.translate("New password") }}</span>
        <input id="new-password" class="input" type="email">
    </label>

    <label for="confirm-password" class="label">
        <span class="txt">{{ language.translate("Confirm password") }}</span>
        <input id="confirm-password" class="input" type="email">
    </label>

    <div class="cl">
        <a href="javascript:void(0);" class="btn blue submit right">{{ language.translate("Request new password") }}</a>
    </div>
</form>