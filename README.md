# Project Documentation

## Installation and Running the App

To install and run the application, follow these steps:

1. Clone the repository:
`git clone https://github.com/your-username/your-repository.git`

2. Navigate to the project directory:
   cd your-repository
3. Install dependencies:
   composer install

4. Set up the database:

- Create a new database in your local environment.
- Update the `.env` file with your database credentials.
- Run the migration command:

  ``` php

  php artisan migrate
  ```

5. Start the development server:

    - Run the server command:

  ``` php

  php artisan serve
  ```

## Functionalities

The application provides the following functionalities:

1. **User Registration:**

- Users can register as patients, doctors, or administrators.
- Patient registration includes basic information such as name and email.
- Doctor and admin registration require additional information.

 **User Login:**

- Users can log in using their email and password.
- The application authenticates the user and generates an API token for subsequent requests.

 **User Logout:**

- Users can log out by invalidating their API token.

 **Password Recovery:**

- Users can request a password reset link by providing their email.
- The application generates an OTP (One-Time Password) and sends it to the user's email.
- Users can verify the OTP and reset their password.

2. **Patient Management:**

- Patients can view their personal information.
- Patients can view their medical records.
- Patients can schedule appointments with doctors.

3. **Doctor Management:**

- Doctors can view their personal information.
- Doctors can view their appointments.
- Doctors can provide recommendations for patients.

4. **Admin Management:**

- Admins can view their personal information.
- Admins can manage user registrations, including approving or rejecting doctor and admin registrations.

## API Endpoints

The application provides the following API endpoints:

- `GET|HEAD  /` - Home endpoint
- `GET|HEAD  api/admin/all/system/records` - Get all system records for admins
- `GET|HEAD  api/admin/system/users` - Get system users for admins
- `DELETE    api/admin/user/{id}` - Delete a user by ID for admins
- `GET|HEAD  api/analytics/all/records` - Get all records for analytics
- `POST      api/auth/adm/register` - Register admin
- `POST      api/auth/doc/register` - Register doctor
- `POST      api/auth/login` - User login
- `GET|HEAD  api/auth/logout` - User logout
- `POST      api/auth/pa/register` - Register patient
- `POST      api/doctor/add-symptom` - Add symptom for doctor
- `GET|HEAD  api/doctor/alerts` - Get alerts for doctor
- `GET|HEAD  api/doctor/all/medical/records` - Get all medical records for doctor
- `GET|HEAD  api/doctor/appointments` - Get appointments for doctor
- `PUT       api/doctor/appointments/close` - Close appointment for doctor
- `GET|HEAD  api/doctor/dash/data` - Get dashboard data for doctor
- `GET|HEAD  api/doctor/medical-record/{id}` - Get medical record by ID for doctor
- `POST      api/doctor/recommendation` - Provide recommendation for doctor
- `POST      api/pass/res/new-pass` - Request new password for patient
- `POST      api/pass/res/request-otp` - Request OTP for password reset for patient
- `POST      api/pass/res/verify-otp` - Verify OTP for password reset for patient
- `POST      api/patient/add-record` - Add medical record for patient
- `POST      api/patient/book-appointment` - Book appointment for patient
- `POST      api/patient/chat` - Start chat for patient
- `GET|HEAD  api/patient/dash/data` - Get dashboard data for patient
- `GET|HEAD  api/patient/doctors` - Get doctors for patient
- `PUT       api/patient/profile/update` - Update patient profile
- `GET|HEAD  api/patient/record/{id}` - Get medical record by ID for patient
- `DELETE    api/patient/record/{id}` - Delete medical record by ID for patient
- `GET|HEAD  api/patient/records` - Get all medical records for patient
- `GET|HEAD  api/patient/symptoms` - Get symptoms for patient
- `GET|HEAD  api/user/profile` - Get user profile
- `GET|HEAD  sanctum/csrf-cookie` - Get Sanctum CSRF cookie
- `GET|HEAD  up` - Health check endpoint
