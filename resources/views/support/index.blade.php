@extends('layouts.app')
@section('title', 'Support — BuildScape CMS')
@section('page-title', 'Support & Help')

@section('content')

@if(session('success'))
<script>document.addEventListener('DOMContentLoaded',()=>toast(@json(session('success')),'success'))</script>
@endif

<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:16px;margin-bottom:24px">
    {{-- Quick Help KPIs --}}
    <div class="kpi" style="--ac:linear-gradient(90deg,#1565C0,#42A5F5)">
        <div class="kl">Documentation</div>
        <div class="kv" style="font-size:14px;color:#42A5F5">User Guides</div>
        <div class="kd kd-n">Step-by-step tutorials</div>
    </div>
    <div class="kpi" style="--ac:linear-gradient(90deg,#00897B,#4DB6AC)">
        <div class="kl">System Status</div>
        <div class="kv" style="font-size:14px;color:#4DB6AC">Operational</div>
        <div class="kd kd-up">All systems running</div>
    </div>
    <div class="kpi" style="--ac:linear-gradient(90deg,#6A1B9A,#AB47BC)">
        <div class="kl">Response Time</div>
        <div class="kv" style="font-size:14px;color:#AB47BC">&lt; 24h</div>
        <div class="kd kd-n">Business hours support</div>
    </div>
</div>

{{-- Common Questions & Quick Links --}}
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(320px,1fr));gap:20px;margin-bottom:24px">
    {{-- Frequently Asked Questions --}}
    <div class="card">
        <div class="ch" style="padding-bottom:12px;border-bottom:1px solid var(--bd)">
            <div class="ct">
                <span class="material-symbols-outlined" style="font-size:20px;margin-right:8px;vertical-align:middle;color:var(--blue)">help</span>
                Frequently Asked Questions
            </div>
        </div>
        <div style="padding:16px">
            <div style="margin-bottom:16px">
                <div style="font-size:13px;font-weight:600;color:var(--t1);margin-bottom:6px;cursor:pointer"
                     onclick="this.nextElementSibling.style.display=this.nextElementSibling.style.display==='none'?'block':'none'">
                    How do I create a new project?
                </div>
                <div style="font-size:12px;color:var(--t3);line-height:1.6;display:none;padding:10px;background:var(--card2);border-radius:8px">
                    Navigate to the Projects page from the sidebar, then click the "Create Project" button. Fill in the required fields and submit.
                </div>
            </div>
            <div style="margin-bottom:16px">
                <div style="font-size:13px;font-weight:600;color:var(--t1);margin-bottom:6px;cursor:pointer"
                     onclick="this.nextElementSibling.style.display=this.nextElementSibling.style.display==='none'?'block':'none'">
                    How can I assign tasks to team members?
                </div>
                <div style="font-size:12px;color:var(--t3);line-height:1.6;display:none;padding:10px;background:var(--card2);border-radius:8px">
                    Go to the Tasks page, create or edit a task, and select a team member from the "Assigned To" dropdown.
                </div>
            </div>
            <div style="margin-bottom:16px">
                <div style="font-size:13px;font-weight:600;color:var(--t1);margin-bottom:6px;cursor:pointer"
                     onclick="this.nextElementSibling.style.display=this.nextElementSibling.style.display==='none'?'block':'none'">
                    How do I track budget expenses?
                </div>
                <div style="font-size:12px;color:var(--t3);line-height:1.6;display:none;padding:10px;background:var(--card2);border-radius:8px">
                    Visit the Budget section to view and manage expenses. You can filter by project, category, and date range.
                </div>
            </div>
            <div style="margin-bottom:16px">
                <div style="font-size:13px;font-weight:600;color:var(--t1);margin-bottom:6px;cursor:pointer"
                     onclick="this.nextElementSibling.style.display=this.nextElementSibling.style.display==='none'?'block':'none'">
                    How do I generate reports?
                </div>
                <div style="font-size:12px;color:var(--t3);line-height:1.6;display:none;padding:10px;background:var(--card2);border-radius:8px">
                    Navigate to the Reports section, select the report type, and choose your desired export format (PDF, Excel, CSV).
                </div>
            </div>
            <div>
                <div style="font-size:13px;font-weight:600;color:var(--t1);margin-bottom:6px;cursor:pointer"
                     onclick="this.nextElementSibling.style.display=this.nextElementSibling.style.display==='none'?'block':'none'">
                    How can I manage user permissions?
                </div>
                <div style="font-size:12px;color:var(--t3);line-height:1.6;display:none;padding:10px;background:var(--card2);border-radius:8px">
                    Admin users can manage permissions through the Users and Roles sections in the sidebar.
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Links & Resources --}}
    <div class="card">
        <div class="ch" style="padding-bottom:12px;border-bottom:1px solid var(--bd)">
            <div class="ct">
                <span class="material-symbols-outlined" style="font-size:20px;margin-right:8px;vertical-align:middle;color:var(--green)">link</span>
                Quick Links & Resources
            </div>
        </div>
        <div style="padding:16px">
            <div style="display:flex;flex-direction:column;gap:12px">
                <a href="{{ route('dashboard') }}"
                   style="display:flex;align-items:center;gap:12px;padding:12px;background:var(--card2);border-radius:8px;text-decoration:none;color:var(--t1);transition:background .15s"
                   onmouseenter="this.style.background='rgba(21,101,192,.1)'"
                   onmouseleave="this.style.background='var(--card2)'">
                    <span class="material-symbols-outlined" style="color:var(--blue)">dashboard</span>
                    <div>
                        <div style="font-size:13px;font-weight:600">Dashboard</div>
                        <div style="font-size:11px;color:var(--t3)">Overview and KPIs</div>
                    </div>
                </a>
                <a href="{{ route('projects.index') }}"
                   style="display:flex;align-items:center;gap:12px;padding:12px;background:var(--card2);border-radius:8px;text-decoration:none;color:var(--t1);transition:background .15s"
                   onmouseenter="this.style.background='rgba(21,101,192,.1)'"
                   onmouseleave="this.style.background='var(--card2)'">
                    <span class="material-symbols-outlined" style="color:var(--blue)">construction</span>
                    <div>
                        <div style="font-size:13px;font-weight:600">Projects</div>
                        <div style="font-size:11px;color:var(--t3)">Manage and track projects</div>
                    </div>
                </a>
                <a href="{{ route('tasks.index') }}"
                   style="display:flex;align-items:center;gap:12px;padding:12px;background:var(--card2);border-radius:8px;text-decoration:none;color:var(--t1);transition:background .15s"
                   onmouseenter="this.style.background='rgba(21,101,192,.1)'"
                   onmouseleave="this.style.background='var(--card2)'">
                    <span class="material-symbols-outlined" style="color:var(--green)">assignment</span>
                    <div>
                        <div style="font-size:13px;font-weight:600">Tasks</div>
                        <div style="font-size:11px;color:var(--t3)">Task management</div>
                    </div>
                </a>
                <a href="{{ route('team.index') }}"
                   style="display:flex;align-items:center;gap:12px;padding:12px;background:var(--card2);border-radius:8px;text-decoration:none;color:var(--t1);transition:background .15s"
                   onmouseenter="this.style.background='rgba(21,101,192,.1)'"
                   onmouseleave="this.style.background='var(--card2)'">
                    <span class="material-symbols-outlined" style="color:var(--purple)">group</span>
                    <div>
                        <div style="font-size:13px;font-weight:600">Team</div>
                        <div style="font-size:11px;color:var(--t3)">Team members and workload</div>
                    </div>
                </a>
                <a href="{{ route('reports.index') }}"
                   style="display:flex;align-items:center;gap:12px;padding:12px;background:var(--card2);border-radius:8px;text-decoration:none;color:var(--t1);transition:background .15s"
                   onmouseenter="this.style.background='rgba(21,101,192,.1)'"
                   onmouseleave="this.style.background='var(--card2)'">
                    <span class="material-symbols-outlined" style="color:var(--amber)">analytics</span>
                    <div>
                        <div style="font-size:13px;font-weight:600">Reports</div>
                        <div style="font-size:11px;color:var(--t3)">Generate and export reports</div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

{{-- System Information & Contact --}}
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(320px,1fr));gap:20px">
    {{-- System Information --}}
    <div class="card">
        <div class="ch" style="padding-bottom:12px;border-bottom:1px solid var(--bd)">
            <div class="ct">
                <span class="material-symbols-outlined" style="font-size:20px;margin-right:8px;vertical-align:middle;color:var(--cyan)">info</span>
                System Information
            </div>
        </div>
        <div style="padding:16px">
            <div style="display:flex;flex-direction:column;gap:12px">
                <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--bd)">
                    <span style="font-size:12px;color:var(--t3)">Application</span>
                    <span style="font-size:12px;font-weight:600;color:var(--t1)">BuildScape CMS</span>
                </div>
                <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--bd)">
                    <span style="font-size:12px;color:var(--t3)">Version</span>
                    <span style="font-size:12px;font-weight:600;color:var(--t1)">1.0.0</span>
                </div>
                <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--bd)">
                    <span style="font-size:12px;color:var(--t3)">Framework</span>
                    <span style="font-size:12px;font-weight:600;color:var(--t1)">Laravel 11</span>
                </div>
                <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--bd)">
                    <span style="font-size:12px;color:var(--t3)">Environment</span>
                    <span style="font-size:12px;font-weight:600;color:var(--green)">{{ config('app.env') }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;padding:8px 0">
                    <span style="font-size:12px;color:var(--t3)">Last Updated</span>
                    <span style="font-size:12px;font-weight:600;color:var(--t1)">{{ now()->format('M d, Y') }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Contact Support --}}
    <div class="card">
        <div class="ch" style="padding-bottom:12px;border-bottom:1px solid var(--bd)">
            <div class="ct">
                <span class="material-symbols-outlined" style="font-size:20px;margin-right:8px;vertical-align:middle;color:var(--pink)">contact_support</span>
                Contact Support
            </div>
        </div>
        <div style="padding:16px">
            <div style="font-size:13px;color:var(--t2);line-height:1.6;margin-bottom:16px">
                Need additional help? Reach out to our support team through any of these channels:
            </div>
            <div style="display:flex;flex-direction:column;gap:12px">
                <div style="display:flex;align-items:center;gap:12px;padding:12px;background:var(--card2);border-radius:8px">
                    <span class="material-symbols-outlined" style="color:var(--blue)">email</span>
                    <div>
                        <div style="font-size:11px;color:var(--t3)">Email</div>
                        <div style="font-size:13px;font-weight:600;color:var(--t1)">support@buildscape.com</div>
                    </div>
                </div>
                <div style="display:flex;align-items:center;gap:12px;padding:12px;background:var(--card2);border-radius:8px">
                    <span class="material-symbols-outlined" style="color:var(--green)">phone</span>
                    <div>
                        <div style="font-size:11px;color:var(--t3)">Phone</div>
                        <div style="font-size:13px;font-weight:600;color:var(--t1)">+1 (555) 123-4567</div>
                    </div>
                </div>
                <div style="display:flex;align-items:center;gap:12px;padding:12px;background:var(--card2);border-radius:8px">
                    <span class="material-symbols-outlined" style="color:var(--purple)">schedule</span>
                    <div>
                        <div style="font-size:11px;color:var(--t3)">Support Hours</div>
                        <div style="font-size:13px;font-weight:600;color:var(--t1)">Mon-Fri, 9AM-6PM</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-expand first FAQ on load
document.addEventListener('DOMContentLoaded', function() {
    const firstFaq = document.querySelector('[onclick*="nextElementSibling"]');
    if (firstFaq) {
        firstFaq.nextElementSibling.style.display = 'block';
    }
});
</script>

@endsection
