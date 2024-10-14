<x-app-layout>
    <x-slot name="header">
        <a href="{{ route('categoriesListing') }}">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Categories List') }}
            </h2>
        </a>
    </x-slot>
    {{-- {{dd($category)}} --}}
    <x-guest-layout>
        <form method="POST" action="{{ route('categories.update') }}" enctype="multipart/form-data">
            @csrf

            <div>
                <x-input-label for="title" :value="__('Title')" />
                <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" value="{{$category->title}}"
                    required autofocus autocomplete="title" />
                <x-input-error :messages="$errors->get('title')" class="mt-2" />
            </div>

            <!-- description -->
            <div class="mt-4">
                <x-input-label for="description" :value="__('Description')" />
                <x-text-input id="description" class="block mt-1 w-full" type="text" name="description"
                    value="{{$category->description}}" required autofocus autocomplete="description" />
                <x-input-error :messages="$errors->get('description')" class="mt-2" />
            </div>
            <input type="hidden" name="id" value="{{$category->id}}">

            <!-- image Address -->
            <div class="mt-4">
                <x-input-label for="image" :value="__('Image')" />
                <x-text-input id="image" class="block mt-1 w-full" type="file" name="image" value="{{$category->image}}"
                     autocomplete="image" />
                <x-input-error :messages="$errors->get('image')" class="mt-2" />
            </div>

            <!-- radio buttons -->
            <div class="mt-4">
                <x-input-label for="options" :value="__('Select Option')" />
                <div class="flex mt-2">
                    <label class="mr-4">
                        <input type="radio" name="status" value="1"
                            {{ $category->status == '1' ? 'checked' : '' }} required />
                        Active
                    </label>&nbsp; &nbsp; &nbsp;
                    <label>
                        <input type="radio" name="status" value="0"
                            {{ $category->status == '0' ? 'checked' : '' }} required />
                        Inactive
                    </label>
                </div>
                <x-input-error :messages="$errors->get('status')" class="mt-2" />
            </div>


            <div class="flex items-center justify-end mt-4">

                <x-primary-button class="ms-4">
                    {{ __('Add Categories') }}
                </x-primary-button>
            </div>
        </form>
    </x-guest-layout>

</x-app-layout>
