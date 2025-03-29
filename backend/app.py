from flask import Flask, jsonify
from flask_cors import CORS
from flask_sqlalchemy import SQLAlchemy
from flask_jwt_extended import JWTManager
from datetime import timedelta
import os

app = Flask(__name__)
CORS(app)

# 데이터베이스 설정
app.config['SQLALCHEMY_DATABASE_URI'] = 'mysql+pymysql://root:password@localhost/mental_health_center'
app.config['SQLALCHEMY_TRACK_MODIFICATIONS'] = False

# JWT 설정
app.config['JWT_SECRET_KEY'] = 'your-secret-key'  # 실제 운영 환경에서는 환경 변수로 관리
app.config['JWT_ACCESS_TOKEN_EXPIRES'] = timedelta(hours=1)

db = SQLAlchemy(app)
jwt = JWTManager(app)

# 사용자 모델
class User(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    username = db.Column(db.String(80), unique=True, nullable=False)
    email = db.Column(db.String(120), unique=True, nullable=False)
    password = db.Column(db.String(120), nullable=False)
    role = db.Column(db.String(20), nullable=False, default='user')  # user, admin
    created_at = db.Column(db.DateTime, server_default=db.func.now())

@app.route('/')
def home():
    return jsonify({"message": "심리지원발달센터 API 서버"})

if __name__ == '__main__':
    with app.app_context():
        db.create_all()
    app.run(debug=True) 