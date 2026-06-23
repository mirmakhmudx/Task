@auth
    @if(auth()->id() !== $advert->user_id && $advert->isActive())
        <div class="card" style="border:1px solid #e5e7eb;border-radius:14px;max-width:640px;margin-top:24px;">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Sotuvchiga yozish</h6>

                @if($errors->any())
                    <div class="mb-2" style="font-size:0.85rem;color:#dc2626;">
                        @foreach($errors->all() as $error){{ $error }}@endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('cabinet.dialogs.write', $advert) }}">
                    @csrf
                    <div class="mb-2">
                        <textarea name="message" rows="3" class="form-control" style="border-radius:10px;"
                                  placeholder="Xabaringiz...">{{ old('message') }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Send Message</button>
                </form>
            </div>
        </div>
    @endif
@endauth
