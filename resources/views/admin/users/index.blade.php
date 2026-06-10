@extends('layouts.admin')
@section('title', 'Users')

@section('content')

<div class="card-clean">
    <div class="p-4" style="border-bottom:1px solid var(--border);">
        <p style="font-size:0.82rem;color:var(--text-muted);margin:0;">{{ $users->total() }} users registered</p>
    </div>

    <div class="table-responsive">
        <table class="table table-admin mb-0">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Phone</th>
                    <th>Role</th>
                    <th>Joined</th>
                    <th>Change Role</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>
                            <div style="font-size:0.88rem;font-weight:600;font-family:'Sora',sans-serif;">{{ $user->name }}</div>
                            <div style="font-size:0.75rem;color:var(--text-muted);">{{ $user->email }}</div>
                        </td>
                        <td style="font-size:0.82rem;color:var(--text-muted);">{{ $user->phone ?? '—' }}</td>
                        <td>
                            <span class="badge-status"
                                style="{{ $user->role === 'admin' ? 'background:#fee2e2;color:#991b1b;' : 'background:#dbeafe;color:#1e40af;' }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td style="font-size:0.78rem;color:var(--text-muted);">{{ $user->created_at->format('d M Y') }}</td>
                        <td>
                            @if($user->id !== auth()->id())
                                <form method="POST" action="{{ route('admin.users.update-role', $user) }}" class="d-flex gap-2 align-items-center">
                                    @csrf @method('PATCH')
                                    <select name="role" class="form-select" style="width:auto;font-size:0.82rem;min-width:110px;">
                                        <option value="customer" {{ $user->role === 'customer' ? 'selected' : '' }}>Customer</option>
                                        <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                    </select>
                                    <button type="submit" class="btn-brand" style="padding:0.35rem 0.75rem;font-size:0.8rem;border-radius:7px;">
                                        Save
                                    </button>
                                </form>
                            @else
                                <span style="font-size:0.78rem;color:var(--text-muted);">You</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-5">No users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="d-flex justify-content-center mt-4">
    {{ $users->links() }}
</div>
@endsection
