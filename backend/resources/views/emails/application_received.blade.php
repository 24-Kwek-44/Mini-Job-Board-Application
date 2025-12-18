<!DOCTYPE html>
<html>

<head>
  <style>
    body {
      font-family: sans-serif;
      line-height: 1.6;
      color: #333;
    }

    .container {
      max-width: 600px;
      margin: 0 auto;
      padding: 20px;
    }

    .header {
      background: #4f46e5;
      color: white;
      padding: 15px;
      border-radius: 8px 8px 0 0;
    }

    .content {
      border: 1px solid #e5e7eb;
      padding: 20px;
      border-radius: 0 0 8px 8px;
    }

    .field {
      margin-bottom: 10px;
    }

    .label {
      font-weight: bold;
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="header">
      <h2>New Job Application</h2>
    </div>
    <div class="content">
      <p>Hello,</p>
      <p>You have received a new application for the position of <strong>{{ $application->job->title }}</strong>.</p>

      <div class="field">
        <div class="label">Applicant Name:</div>
        <div>{{ $application->applicant->name }}</div>
      </div>

      <div class="field">
        <div class="label">Applicant Email:</div>
        <div>{{ $application->applicant->email }}</div>
      </div>

      <div class="field">
        <div class="label">Message:</div>
        <div>{{ $application->message }}</div>
      </div>

      <p>Please log in to your dashboard to view the resume and manage this application.</p>

      <p>Best regards,<br>JobSphere Team</p>
    </div>
  </div>
</body>

</html>