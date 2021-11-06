<div class="form-group mt-20">
	<div class="captcha">
		{!! Captcha::img() !!}
	</div>
	<input type="text" id="captcha" name="captcha">
	<button type="submit">Go</button>
	@error('captcha')
	<div class="error">
		<small class="text-danger">{{ $errors->first('captcha') }}</small>
	</div>
	@enderror
</div>