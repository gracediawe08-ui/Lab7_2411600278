<x-guest-layout>

<style>
    body {
        background: #FFF7F8 !important;
    }

    .login-page {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 30px 20px;
        background:
            radial-gradient(circle at top left, #FCE4E8 0%, transparent 35%),
            radial-gradient(circle at bottom right, #F2E8F0 0%, transparent 35%),
            #FFF7F8;
    }

    .login-wrapper {
        width: 100%;
        max-width: 430px;
    }

    .login-card {
        background: #ffffff;
        border: 1px solid #F1E5EA;
        border-radius: 24px;
        padding: 35px;
        box-shadow: 0 15px 45px rgba(74, 59, 71, 0.10);
    }

    .login-brand {
        text-align: center;
        margin-bottom: 30px;
    }

    .brand-icon {
        width: 68px;
        height: 68px;
        margin: 0 auto 15px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 18px;

        background: linear-gradient(
            135deg,
            #8E5A83,
            #E5989B
        );

        color: white;
        font-size: 30px;

        box-shadow:
            0 10px 25px rgba(142, 90, 131, .20);
    }

    .login-title {
        margin: 0;
        color: #4A3B47;
        font-size: 26px;
        font-weight: 800;
    }

    .login-subtitle {
        margin-top: 7px;
        color: #8A7B86;
        font-size: 13px;
        line-height: 1.5;
    }

    .form-group {
        margin-bottom: 18px;
    }

    .form-label {
        display: block;
        margin-bottom: 8px;
        color: #5C4B56;
        font-size: 13px;
        font-weight: 700;
    }

    .form-input {
        width: 100%;
        box-sizing: border-box;

        padding: 12px 14px;

        border: 1px solid #DED2DA;
        border-radius: 11px;

        background: #FFFCFD;
        color: #4A3B47;

        font-size: 14px;
        outline: none;

        transition: .2s ease;
    }

    .form-input:focus {
        border-color: #8E5A83;

        box-shadow:
            0 0 0 3px rgba(142, 90, 131, .10);

        background: white;
    }

    .remember-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        margin: 5px 0 20px;
    }

    .remember-label {
        display: flex;
        align-items: center;
        gap: 8px;

        color: #756671;
        font-size: 12px;
    }

    .remember-checkbox {
        width: 15px;
        height: 15px;
        accent-color: #8E5A83;
    }

    .forgot-link {
        color: #8E5A83;
        text-decoration: none;
        font-size: 12px;
        font-weight: 700;
    }

    .forgot-link:hover {
        color: #C06C84;
        text-decoration: underline;
    }

    .sign-in-button {
        width: 100%;

        border: none;
        border-radius: 12px;

        padding: 13px;

        background: linear-gradient(
            135deg,
            #8E5A83,
            #C06C84,
            #E5989B
        );

        color: white;

        font-size: 14px;
        font-weight: 800;

        cursor: pointer;

        box-shadow:
            0 9px 22px rgba(142, 90, 131, .18);

        transition: .2s ease;
    }

    .sign-in-button:hover {
        transform: translateY(-1px);
        box-shadow:
            0 12px 26px rgba(142, 90, 131, .23);
    }

    .error-message {
        margin-top: 6px;
        color: #B23B58;
        font-size: 12px;
    }

    .session-message {
        margin-bottom: 18px;
        padding: 12px 14px;
        border-radius: 11px;
        background: #EAF6EF;
        color: #43815E;
        font-size: 12px;
        font-weight: 600;
    }

    .login-footer {
        margin-top: 22px;
        text-align: center;
        color: #9A8B95;
        font-size: 11px;
    }

    .system-name {
        color: #8E5A83;
        font-weight: 700;
    }

    @media (max-width: 500px) {
        .login-page {
            padding: 20px 14px;
        }

        .login-card {
            padding: 25px 20px;
        }

        .remember-row {
            align-items: flex-start;
            flex-direction: column;
        }
    }

    .brand-icon {
width: 80px;
height: 80px;
margin: 0 auto 15px;


display: flex;
align-items: center;
justify-content: center;

border-radius: 16px;

background: #ffffff;

overflow: hidden;

box-shadow: 0 8px 20px rgba(74, 59, 71, 0.12);

padding: 6px;


}

.login-logo {
width: 100%;
height: 100%;
object-fit: contain;
display: block;
}

</style>

<div class="login-page">

    <div class="login-wrapper">

        <div class="login-card">

            <div class="login-brand">
 
                <h1 class="login-title">
                    Hardware Inventory System
                </h1>

                <p class="login-subtitle">
                    Sign in to manage construction materials and inventory.
                </p>

            </div>


            @if (session('status'))
                <div class="session-message">
                    {{ session('status') }}
                </div>
            @endif


            <x-auth-session-status
                class="mb-4"
                :status="session('status')"
            />


            <form method="POST" action="{{ route('login') }}">

                @csrf


                <div class="form-group">

                    <label
                        for="email"
                        class="form-label"
                    >
                        Email
                    </label>

                    <input
                        id="email"
                        class="form-input"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="username"
                        placeholder="Enter your email"
                    >

                    @if ($errors->get('email'))
                        <div class="error-message">
                            {{ $errors->first('email') }}
                        </div>
                    @endif

                </div>


                <div class="form-group">

                    <label
                        for="password"
                        class="form-label"
                    >
                        Password
                    </label>

                    <input
                        id="password"
                        class="form-input"
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        placeholder="Enter your password"
                    >

                    @if ($errors->get('password'))
                        <div class="error-message">
                            {{ $errors->first('password') }}
                        </div>
                    @endif

                </div>

                    @if (Route::has('password.request'))

                        <a
                            class="forgot-link"
                            href="{{ route('password.request') }}"
                        >
                            Forgot password?
                        </a>

                    @endif

                </div>


                <button
                    type="submit"
                    class="sign-in-button"
                >
                    Sign In
                </button>

            </form>


            <div class="login-footer">

                <span class="system-name">
                    Hardware Inventory System
                </span>

                <br>

                Construction Materials Management

            </div>

        </div>

    </div>

</div>


</x-guest-layout>
