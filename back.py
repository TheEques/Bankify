from flask import Flask, render_template, redirect, request, url_for, session
import subprocess
import mysql.connector
import pickle

app = Flask(__name__)
app.secret_key = b'_5#y2L"F4Q8z\n\xec]/'

# Load your machine learning model from a .pkl file using pickle
with open(r"C:\xampp\htdocs\Bankify1\trained_model\model.pkl", 'rb') as model_file:
    model = pickle.load(model_file)

# Function to connect to MySQL database
def connect_to_database():
    return mysql.connector.connect(
        host="localhost",
        user="root",
        password="",
        database="bankify"
    )

# Function to fetch total deposits and withdrawals for a user
def fetch_user_transactions(user_email):
    conn = connect_to_database()
    cursor = conn.cursor()

    # Query total deposits and withdrawals for the user
    query = f"SELECT SUM(CASE WHEN type = 'deposit' THEN amount ELSE 0 END), " \
            f"SUM(CASE WHEN type = 'withdraw' THEN amount ELSE 0 END) " \
            f"FROM transaction_history WHERE email = '{user_email}'"
    cursor.execute(query)
    row = cursor.fetchone()
    total_deposits = row[0] or 0
    total_withdrawals = row[1] or 0

    conn.close()
    return total_deposits, total_withdrawals

# Function to fetch credit history for a user
def fetch_credit_history(user_email):
    conn = connect_to_database()
    cursor = conn.cursor()

    # Query credit history for the user
    query = f"SELECT credit_history FROM total_transactions WHERE user_email = '{user_email}' ORDER BY id DESC LIMIT 1"
    cursor.execute(query)
    row = cursor.fetchone()
    if row:
        credit_history = row[0]
    else:
        credit_history = 0

    conn.close()
    return credit_history

@app.route('/')
def home():
    # Check if user is logged in
    if 'user_email' in session:
        user_email = session['user_email']
        
        # Fetch total deposits, withdrawals, and credit history for the user
        total_deposits, total_withdrawals = fetch_user_transactions(user_email)
        credit_history = fetch_credit_history(user_email)
        
        return render_template('input.html', credit_history=credit_history)
    else:
        return redirect(url_for('login'))  # Redirect to login page if user is not logged in

@app.route('/predict', methods=['POST'])
def predict():
    if request.method == 'POST':
        # Get user input from the form
        gender = float(request.form['gender'])
        married = float(request.form['married'])
        dependents = float(request.form['dependents'])
        education = float(request.form['education'])
        self_employed = float(request.form['self_employed'])
        applicant_income = float(request.form['applicant_income'])
        coapplicant_income = float(request.form['coapplicant_income'])
        loan_amount = float(request.form['loan_amount'])
        loan_amount_term = float(request.form['loan_amount_term'])
        property_area = float(request.form['property_area'])
        credit_history = float(request.form['credit_history'])  # Retrieve credit history from form data

        # Create a dictionary with the input data
        input_data = {
            'Gender': gender,
            'Married': married,
            'Dependents': dependents,
            'Education': education,
            'Self_Employed': self_employed,
            'ApplicantIncome': applicant_income,
            'CoapplicantIncome': coapplicant_income,
            'LoanAmount': loan_amount,
            'Loan_Amount_Term': loan_amount_term,
            'Property_Area': property_area,
            'Credit_History': credit_history  # Include credit history in input data
        }

        # Make a prediction using the model
        prediction = model.predict([list(input_data.values())])

        # Convert the prediction to a user-friendly message
        if prediction[0] == 1:
            result = "Approved"
        else:
            result = "Not Approved"

        return render_template('result.html', prediction=result)

# Add other routes for login, logout, and executing script as needed
@app.route('/execute_script')
def execute_script():
    try:
        # Execute the Python script using subprocess
        subprocess.run(['python', r'C:\xampp\htdocs\Bankify1\back.py'], check=True)
        message = "Script executed successfully!"
    except subprocess.CalledProcessError as e:
        # Handle errors if the script fails
        message = f"Error: {e.stderr}"

    return render_template('input.html', message=message)
if __name__ == '__main__':
    app.run(debug=True)
