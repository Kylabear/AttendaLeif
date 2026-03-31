<!-- TEST BLADE: update-profile-information-form.blade.php -->
<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>


    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <!-- Profile Photo Upload -->
        <div>
            <x-input-label for="photo" :value="__('Profile Photo')" />
            <div class="flex items-center gap-4 mt-2">
                <img id="profile-photo-preview" 
                    src="{{ $user->photo ? asset('storage/' . $user->photo) : '' }}" 
                    alt="Profile Photo" 
                    class="w-16 h-16 rounded-full object-cover border-2 border-white shadow" 
                    style="aspect-ratio:1/1; {{ $user->photo ? '' : 'display:none;' }}">
                @if (!$user->photo)
                    <span id="profile-photo-placeholder" class="inline-block w-16 h-16 rounded-full bg-gray-200 flex items-center justify-center text-gray-400" style="aspect-ratio:1/1;">No Photo</span>
                @else
                    <span id="profile-photo-placeholder" style="display:none;"></span>
                @endif
                <input id="photo" name="photo" type="file" accept="image/*" class="block text-sm text-gray-500" />
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('photo')" />
        </div>

        <!-- Cropper Modal -->
        <div id="crop-modal" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.7); z-index:9999; align-items:center; justify-content:center;">
            <div style="background:#fff; padding:2rem; border-radius:1rem; max-width:90vw; max-height:90vh; display:flex; flex-direction:column; align-items:center; box-shadow:0 0 0 4px #a78bfa;">
                <img id="photo-preview" style="max-width:300px; max-height:300px; display:block; margin-bottom:1rem; border-radius:50%; border:2px solid #a78bfa;" />
                <div class="flex gap-2 mb-2">
                    <button type="button" id="zoom-in-btn" class="px-3 py-1 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">+</button>
                    <button type="button" id="zoom-out-btn" class="px-3 py-1 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">-</button>
                </div>
                <div class="flex gap-2 mb-2">
                    <button type="button" id="crop-btn" class="px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700">Crop & Use Photo</button>
                    <button type="button" id="crop-close-btn" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">Cancel</button>
                </div>
                <span id="cropper-error" style="color:red; display:none;">Cropper failed to load. Please try another image.</span>
            </div>
        </div>

        <!-- ID Number (Read-only) -->
        <div>
            <x-input-label for="id_number" :value="__('ID Number')" />
            <x-text-input id="id_number" name="id_number" type="text" class="mt-1 block w-full bg-gray-100 cursor-not-allowed" :value="$user->id_number" readonly disabled />
        </div>

        <!-- Filerobot Image Editor CSS/JS -->
        <link rel="stylesheet" href="/css/cropper.min.css">
        <script src="/js/cropper.min.js"></script>
        <script src="/js/cropper-init.js"></script>

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
