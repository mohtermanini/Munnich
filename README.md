# Project Documentation

## Instructions for Setup and Running the Application

### Steps to Set Up the Application

1. **Direct Access**:
   - No setup is required; the application can be directly accessed from the following link:
     [http://d741f17c3a3e47b1.assessment.munnich.it/](http://d741f17c3a3e47b1.assessment.munnich.it/).
   - The database is already set up using the credentials provided in the assessment.

2. **Database Information**:
   - **Schema**:
     - The database schema is available in the `/sql/schema` file.
   - **Seed Data**:
     - Some seed data is included in `/sql/seed.php`. This script can be executed to seed the tables after setting up the database credentials inside the `.env` file.

3. **Source Code**:
   - The source code for the application is hosted on GitHub: [https://github.com/mohtermanini/Munnich](https://github.com/mohtermanini/Munnich).

4. **Login Credentials**:
   - **Username**: `test_user`
   - **Password**: `Password@123`

### Notes
- Ensure the application has appropriate file permissions for reading and writing data if running locally.
- No additional server requirements are needed.

---

## Code Structure and Design Decisions

### Overview
The project follows a custom MVC (Model-View-Controller) architecture to maintain separation of concerns and code modularity. Below is a brief overview of the code structure:

### Folder Structure

- **`app`**:
  - **Controllers**: Handles HTTP requests and interacts with models, middlewares, and views.
  - **Middlewares**: Contains middleware classes for handling cross-cutting concerns like CSRF protection.
  - **Models**: Manages the application’s data logic and database interactions.
  - **Resources**: Handles JSON-formatted responses, including data sanitization, such as in `TripResource`.
  - **Services**: Encapsulates business logic for complex operations (e.g., trip management).
  - **Utils**: Contains utility classes for shared functionality like session management and routing.

- **`config`**:
  - **Database.php**: Handles database connections and configurations.

- **`public`**:
  - **Assets**: Contains static files like CSS, JavaScript, and images.

- **`resources`**:
  - **Components**: Reusable components used within views.
  - **Views**: PHP files for rendering the user interface.

- **`sql`**:
  - **Schema**: Contains the database schema for creating tables.
  - **Seed**: SQL file to populate initial data.
  - **Truncate-tables**: SQL script to clear tables during development.

- **`vendor`**:
  - Contains dependencies installed via Composer.

### Key Design Decisions

1. **Custom MVC Framework**:
   - Pure PHP implementation allows complete flexibility and control without relying on external frameworks.

2. **Security Measures**:
   - Implemented CSRF protection to secure forms and API endpoints against cross-site request forgery.
   - Used server-side validation to complement client-side validations.
   - Added sanitization of JSON responses via `Resources` to ensure safe data handling.

3. **Reusability**:
   - Modular utilities and services ensure code reusability across different features.
   - Example: `CsrfMiddleware` handles CSRF tokens seamlessly across multiple forms.

4. **User Experience**:
   - Responsive frontend with pagination, dropdowns, and modal forms for user convenience.
   - Clear error messages for validation failures.

---

## Assumptions and Considerations

1. **Assumptions**:
   - The application will be accessed via the provided URL and does not need to support local server installations.
   - Login credentials are predefined for evaluation purposes.

2. **Development Considerations**:
   - **Security**:
     - CSRF tokens are tied to individual forms, ensuring secure submission for each endpoint.
     - Passwords are hashed for secure storage.

3. **Constraints**:
   - Development time was limited, so advanced features like unit tests were not implemented.
   - The focus was on functionality and maintaining clean, modular code.
   - A throttling limit is implemented to prevent abuse of application endpoints and ensure fair usage.

4. **Scalability**:
   - While the application is designed for a single-user scenario, the modular architecture allows for easy scaling by introducing features like role-based access control or multi-user management.

---

## Conclusion
This application demonstrates a structured approach to developing a secure and modular web application using pure PHP. It adheres to industry best practices for MVC design and security, ensuring a robust and maintainable codebase.
