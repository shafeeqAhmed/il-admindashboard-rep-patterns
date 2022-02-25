<h6><i class="step-icon ft-file-text"></i>Step 3</h6>
<fieldset>
    <div class="row">
        @foreach($record['boat_services'] as $service)
        <div class="col-md-4">
            <div class="form-group pb-1">
                <input type="checkbox" class="switchery" checked  readonly/>
                <label class="font-medium-2 text-bold-600 ml-1">
                {{ $service['name'] }}
                </label>
            </div>
        </div>
        @endforeach

    </div>



</fieldset>
