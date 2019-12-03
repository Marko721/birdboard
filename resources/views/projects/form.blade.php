
@csrf

<div class="field">
    <label class="label text-sm mb-2 block" for="title">Title</label>

    <div class="control">
        <input
            type="text"
            name="title"
            class="input bg-transparent border border-grey rounded p-2 text-xs w-full{{ $errors->has('email') ? ' is-invalid' : '' }}"
            value="{{ $project->title }}"
            required
        >
    </div>
</div>

<div class="field mt-5">
    <label class="label text-sm mb-2 block" for="description">Description</label>

    <div class="control">
        <textarea
            name="description"
            class="input bg-transparent border border-grey rounded p-2 text-xs w-full{{ $errors->has('email') ? ' is-invalid' : '' }}"
            required
        >{{ $project->description }}</textarea>
    </div>
</div>

<div class="field">
    <div class="control">
        <button type="submit" class="button is-link mt-5">{{ $buttonText }}</button>
        <a href="{{ $project->path() }}">Cancel</a>
    </div>
</div>

@if ($errors->any())
    <div class="field mt-6">

        @foreach ($errors->all() as $error)
            <li class="text-sm text-red">{{ $error }}</li>
        @endforeach

    </div>
@endif
