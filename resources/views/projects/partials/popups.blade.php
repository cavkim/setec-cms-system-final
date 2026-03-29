<div class="popup-overlay" id="create-popup" onclick="if(event.target===this)closeCreatePopup()">
    <div class="popup-box">

        <div class="popup-head">
            <div
                style="width:34px;height:34px;border-radius:9px;background:rgba(21,101,192,.25);border:1px solid rgba(66,165,245,.2);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <svg width="16" height="16" viewBox="0 0 20 20" fill="#42A5F5">
                    <path d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                </svg>
            </div>
            <div>
                <div class="popup-title">Create New Project</div>
                <div style="font-size:11px;color:#4A6880;margin-top:1px">Fill in the details below to create a new
                    project</div>
            </div>
            <div class="popup-close" onclick="closeCreatePopup()">×</div>
        </div>

        <form id="create-form" method="POST" action="{{ route('projects.store') }}">
            @csrf
            <div class="popup-body">

                {{-- Error box --}}
                <div class="f-error" id="create-error"></div>

                {{-- Section: Basic Info --}}
                <div class="f-section">Basic Information</div>

                <div class="f-group">
                    <label class="f-label">Project Name <span>*</span></label>
                    <input type="text" name="project_name" id="c-name" class="f-input"
                        placeholder="e.g. Skyline Tower Phase 2" autocomplete="off" required>
                </div>

                <div class="f-group">
                    <label class="f-label">Location</label>
                    <input type="text" name="location" id="c-loc" class="f-input"
                        placeholder="e.g. Downtown, Phnom Penh" autocomplete="off">
                </div>

                <div class="f-group">
                    <label class="f-label">Description</label>
                    <textarea name="description" id="c-desc" class="f-input f-textarea"
                        placeholder="Brief description of this project..."></textarea>
                </div>

                {{-- Section: Status & Budget --}}
                <div class="f-section">Status & Budget</div>

                <div class="f-grid-2">
                    <div class="f-group">
                        <label class="f-label">Status <span>*</span></label>
                        <select name="status" id="c-status" class="f-input f-select" onchange="updateStatusColor(this)">
                            <option value="planning">Planning</option>
                            <option value="in_progress">In Progress</option>
                            <option value="on_hold">On Hold</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                        <div class="f-hint" id="status-hint"
                            style="display:flex;align-items:center;gap:5px;margin-top:5px">
                            <div id="status-dot"
                                style="width:7px;height:7px;border-radius:50%;background:#64B5F6;flex-shrink:0"></div>
                            <span id="status-label" style="font-size:10px;color:#4A6880">Project is in planning
                                phase</span>
                        </div>
                    </div>
                    <div class="f-group">
                        <label class="f-label">Budget Allocated (USD)</label>
                        <input type="number" name="budget_allocated" id="c-budget" class="f-input"
                            placeholder="e.g. 9000000" min="0" step="1000" oninput="updateBudgetPreview(this.value)">
                        <div class="budget-preview" id="budget-preview">
                            <div style="font-size:10px;color:#4A6880;margin-bottom:3px">Budget preview</div>
                            <div id="budget-formatted" style="font-size:14px;font-weight:700;color:#E8EEF4"></div>
                        </div>
                    </div>
                </div>

                {{-- Section: Timeline --}}
                <div class="f-section">Timeline</div>

                <div class="f-grid-2">
                    <div class="f-group">
                        <label class="f-label">Start Date</label>
                        <input type="date" name="start_date" id="c-start" class="f-input">
                    </div>
                    <div class="f-group">
                        <label class="f-label">End / Deadline Date</label>
                        <input type="date" name="end_date" id="c-end" class="f-input">
                        <div class="f-hint" id="duration-hint"></div>
                    </div>
                </div>

            </div>

            {{-- Footer --}}
            <div class="popup-foot">
                <button type="button" onclick="closeCreatePopup()"
                    style="padding:9px 20px;border-radius:8px;background:transparent;color:#8BAABF;border:1px solid rgba(255,255,255,.08);cursor:pointer;font-family:inherit;font-size:12px;font-weight:500;transition:all .15s">
                    Cancel
                </button>
                <button type="button" onclick="submitCreate()"
                    style="padding:9px 22px;border-radius:8px;background:#1565C0;color:#fff;border:none;cursor:pointer;font-family:inherit;font-size:12px;font-weight:600;transition:background .15s;display:flex;align-items:center;gap:7px"
                    id="create-submit-btn">
                    <svg width="13" height="13" viewBox="0 0 20 20" fill="currentColor">
                        <path
                            d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                    </svg>
                    Create Project
                </button>
            </div>
        </form>

    </div>
</div>


<div class="popup-overlay" id="edit-popup" onclick="if(event.target===this)closeEditPopup()">
    <div class="popup-box">

        <div class="popup-head">
            <div
                style="width:34px;height:34px;border-radius:9px;background:rgba(0,137,123,.2);border:1px solid rgba(77,182,172,.2);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <svg width="16" height="16" viewBox="0 0 20 20" fill="#4DB6AC">
                    <path
                        d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                </svg>
            </div>
            <div>
                <div class="popup-title" id="edit-popup-title">Edit Project</div>
                <div style="font-size:11px;color:#4A6880;margin-top:1px">Update project information and settings</div>
            </div>
            <div class="popup-close" onclick="closeEditPopup()">×</div>
        </div>

        <form id="edit-form" method="POST" action="">
            @csrf
            @method('PUT')
            <div class="popup-body">

                <div class="f-error" id="edit-error"></div>

                <div class="f-section">Basic Information</div>

                <div class="f-group">
                    <label class="f-label">Project Name <span>*</span></label>
                    <input type="text" name="project_name" id="e-name" class="f-input" required>
                </div>

                <div class="f-group">
                    <label class="f-label">Location</label>
                    <input type="text" name="location" id="e-loc" class="f-input">
                </div>

                <div class="f-section">Status & Budget</div>

                <div class="f-grid-2">
                    <div class="f-group">
                        <label class="f-label">Status <span>*</span></label>
                        <select name="status" id="e-status" class="f-input f-select">
                            <option value="planning">Planning</option>
                            <option value="in_progress">In Progress</option>
                            <option value="on_hold">On Hold</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div class="f-group">
                        <label class="f-label">Budget Allocated (USD)</label>
                        <input type="number" name="budget_allocated" id="e-budget" class="f-input" min="0">
                    </div>
                </div>

                <div class="f-section">Progress & Timeline</div>

                <div class="f-group">
                    <label class="f-label">Progress (%)</label>
                    <div style="display:flex;align-items:center;gap:12px;margin-top:4px">
                        <input type="range" name="progress_percent" id="e-progress" min="0" max="100" value="0"
                            style="flex:1" oninput="document.getElementById('e-pv').textContent=this.value+'%'">
                        <span id="e-pv"
                            style="font-size:13px;font-weight:600;color:#E8EEF4;min-width:38px;text-align:right">0%</span>
                    </div>
                    <div
                        style="height:5px;background:rgba(255,255,255,.06);border-radius:5px;overflow:hidden;margin-top:8px">
                        <div id="e-progress-bar"
                            style="height:5px;border-radius:5px;background:#42A5F5;transition:width .2s;width:0%"></div>
                    </div>
                </div>

                <div class="f-grid-2">
                    <div class="f-group">
                        <label class="f-label">Start Date</label>
                        <input type="date" name="start_date" id="e-start" class="f-input">
                    </div>
                    <div class="f-group">
                        <label class="f-label">End Date</label>
                        <input type="date" name="end_date" id="e-end" class="f-input">
                    </div>
                </div>

            </div>

            <div class="popup-foot">
                <button type="button" onclick="closeEditPopup()"
                    style="padding:9px 20px;border-radius:8px;background:transparent;color:#8BAABF;border:1px solid rgba(255,255,255,.08);cursor:pointer;font-family:inherit;font-size:12px;font-weight:500">
                    Cancel
                </button>
                <button type="submit"
                    style="padding:9px 22px;border-radius:8px;background:#00897B;color:#fff;border:none;cursor:pointer;font-family:inherit;font-size:12px;font-weight:600;display:flex;align-items:center;gap:7px">
                    <svg width="13" height="13" viewBox="0 0 20 20" fill="currentColor">
                        <path
                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" />
                    </svg>
                    Save Changes
                </button>
            </div>
        </form>

    </div>
</div>


<div class="popup-overlay" id="delete-popup" onclick="if(event.target===this)closeDeletePopup()">
    <div class="popup-box" style="width:420px">
        <div class="popup-head">
            <div
                style="width:34px;height:34px;border-radius:9px;background:rgba(198,40,40,.2);border:1px solid rgba(198,40,40,.3);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <svg width="16" height="16" viewBox="0 0 20 20" fill="#EF9A9A">
                    <path fill-rule="evenodd"
                        d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" />
                </svg>
            </div>
            <div>
                <div class="popup-title">Delete Project</div>
                <div style="font-size:11px;color:#4A6880;margin-top:1px">This action cannot be undone</div>
            </div>
            <div class="popup-close" onclick="closeDeletePopup()">×</div>
        </div>
        <div class="popup-body" style="padding:20px 22px">
            <div style="font-size:13px;color:#8BAABF;line-height:1.75">
                Are you sure you want to delete
                <strong id="delete-name" style="color:#E8EEF4"></strong>?<br>
                <span style="color:#EF9A9A;font-size:12px">All tasks, expenses, and documents linked to this project
                    will also be deleted.</span>
            </div>
        </div>
        <div class="popup-foot">
            <button onclick="closeDeletePopup()"
                style="padding:9px 20px;border-radius:8px;background:transparent;color:#8BAABF;border:1px solid rgba(255,255,255,.08);cursor:pointer;font-family:inherit;font-size:12px;font-weight:500">
                Cancel
            </button>
            <form id="delete-form" method="POST" style="display:inline">
                @csrf
                @method('DELETE')
                <button type="submit"
                    style="padding:9px 22px;border-radius:8px;background:#C62828;color:#fff;border:none;cursor:pointer;font-family:inherit;font-size:12px;font-weight:600;display:flex;align-items:center;gap:7px">
                    <svg width="13" height="13" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" />
                    </svg>
                    Yes, delete it
                </button>
            </form>
        </div>
    </div>
</div>