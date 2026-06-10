@extends('layouts.app')
@section('title', 'Register')

@section('content')
<div class="container">
    <div style="max-width:420px;margin:2rem auto;">
        <div class="card-clean p-4">
            <h2 style="font-family:'Sora',sans-serif;font-weight:700;font-size:1.5rem;margin-bottom:0.3rem;">Create account</h2>
            <p style="color:var(--text-muted);font-size:0.88rem;margin-bottom:1.5rem;">Join us and start shopping</p>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="mb-3">
                    <label style="font-size:0.8rem;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;color:var(--text-muted);display:block;margin-bottom:0.4rem;">Full Name</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                        style="width:100%;border:1.5px solid var(--border);border-radius:10px;padding:0.6rem 0.9rem;font-size:0.9rem;outline:none;background:var(--surface);"
                        placeholder="Your full name" required autofocus>
                    @error('name')
                        <small style="color:var(--accent);">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label style="font-size:0.8rem;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;color:var(--text-muted);display:block;margin-bottom:0.4rem;">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                        style="width:100%;border:1.5px solid var(--border);border-radius:10px;padding:0.6rem 0.9rem;font-size:0.9rem;outline:none;background:var(--surface);"
                        placeholder="your@email.com" required>
                    @error('email')
                        <small style="color:var(--accent);">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label style="font-size:0.8rem;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;color:var(--text-muted);display:block;margin-bottom:0.4rem;">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                        style="width:100%;border:1.5px solid var(--border);border-radius:10px;padding:0.6rem 0.9rem;font-size:0.9rem;outline:none;background:var(--surface);"
                        placeholder="01XXXXXXXXX" required>
                    @error('phone')
                        <small style="color:var(--accent);">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label style="font-size:0.8rem;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;color:var(--text-muted);display:block;margin-bottom:0.4rem;">Password</label>
                    <input type="password" name="password"
                        style="width:100%;border:1.5px solid var(--border);border-radius:10px;padding:0.6rem 0.9rem;font-size:0.9rem;outline:none;background:var(--surface);"
                        placeholder="••••••••" required>
                    @error('password')
                        <small style="color:var(--accent);">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-4">
                    <label style="font-size:0.8rem;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;color:var(--text-muted);display:block;margin-bottom:0.4rem;">Confirm Password</label>
                    <input type="password" name="password_confirmation"
                        style="width:100%;border:1.5px solid var(--border);border-radius:10px;padding:0.6rem 0.9rem;font-size:0.9rem;outline:none;background:var(--surface);"
                        placeholder="••••••••" required>
                </div>

                <button type="submit"
                    style="width:100%;background:var(--accent);color:#fff;border:none;border-radius:10px;font-family:'Sora',sans-serif;font-size:0.95rem;font-weight:700;padding:0.75rem;cursor:pointer;transition:background 0.2s;">
                    Create Account
                </button>
            </form>

            <p style="text-align:center;margin-top:1.2rem;font-size:0.85rem;color:var(--text-muted);">
                Already have an account?
                <a href="{{ route('login') }}" style="color:var(--accent);font-weight:600;text-decoration:none;">Login</a>
            </p>
        </div>
    </div>
</div>
@endsection
