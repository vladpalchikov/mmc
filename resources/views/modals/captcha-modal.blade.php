<div class="modal captcha-modal" tabindex="-1" role="dialog" id="captchaModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Введите цифры с картинки</h4>
            </div>
            <div class="modal-body">

            	<div class="row">
                    <div class="col-md-12 text-center">
                    	<img src="" id="captcha-img" class="" alt="">
                    </div>
                    <div class="col-md-12 text-center mt10">
                    	<a id="captcha-reload" class="btn btn-primary">Обновить картинку</a>
                    </div>
                </div>

                <div class="row mt20">
                    <div class="form-group col-md-6 col-md-offset-3">
                        <input class="form-control" name="captchaToken" type="hidden">
                        <input class="form-control mt20 text-center" autofocus="autofocus" name="captcha" id="captcha_text" type="text">
                    </div>
                </div>

                <div class="row">
                	<div class="col-md-12 inn-captcha-error hide">
						<div class="alert alert-danger">
						</div>
					</div>
                </div>

            </div>
            <div class="modal-footer modal-new-foreigner">
            	<div class="btn btn-success inn-captcha" type="submit">Продолжить</div>
            </div>
        </div>
    </div>
</div>