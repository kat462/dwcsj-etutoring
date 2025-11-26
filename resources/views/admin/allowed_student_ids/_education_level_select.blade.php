@php use App\Helpers\EducationLevel; @endphp
<div class="mb-3">
    <label for="education_level" class="form-label">Education Level</label>
    <select name="education_level" id="education_level" class="form-select" required>
        <option value="">Select education level</option>
        @foreach(EducationLevel::options() as $key => $label)
            <option value="{{ $key }}" {{ (isset($item) && $item->education_level === $key) ? 'selected' : '' }}>{{ $label }}</option>
        @endforeach
    </select>
</div>