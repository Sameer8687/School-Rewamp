import React from 'react';
import ReactDOM from 'react-dom/client';
import { BrowserRouter } from 'react-router-dom';
import App from './App.jsx';
import './index.css';

class GlobalErrorBoundary extends React.Component {
  constructor(props) {
    super(props);
    this.state = { hasError: false, error: null, errorInfo: null };
  }

  static getDerivedStateFromError(error) {
    return { hasError: true, error };
  }

  componentDidCatch(error, errorInfo) {
    console.error("Global Error Caught:", error, errorInfo);
    this.setState({ error, errorInfo });
  }

  render() {
    if (this.state.hasError) {
      return (
        <div style={{ padding: '30px', fontFamily: 'sans-serif', background: '#F7F7F5', color: '#202020', minHeight: '100vh' }}>
          <div style={{ maxWidth: '800px', margin: '0 auto', background: '#fff', padding: '24px', borderRadius: '16px', border: '1px solid #E7E7E4', boxShadow: '0 4px 14px rgba(0,0,0,0.06)' }}>
            <h2 style={{ fontSize: '18px', fontWeight: 'bold', color: '#b91c1c', margin: '0 0 12px' }}>
              UI Rendering Error
            </h2>
            <p style={{ fontSize: '13px', color: '#73736F', margin: '0 0 16px' }}>
              An error occurred while rendering the dashboard. Below are the details:
            </p>
            <pre style={{ background: '#fee2e2', color: '#991b1b', padding: '14px', borderRadius: '10px', fontSize: '12px', overflowX: 'auto', whiteSpace: 'pre-wrap' }}>
              {String(this.state.error?.stack || this.state.error?.message || this.state.error)}
            </pre>
            {this.state.errorInfo?.componentStack && (
              <pre style={{ background: '#F1F1EF', color: '#4b5563', padding: '14px', borderRadius: '10px', fontSize: '11px', overflowX: 'auto', marginTop: '12px' }}>
                {this.state.errorInfo.componentStack}
              </pre>
            )}
            <button
              onClick={() => window.location.reload()}
              style={{ marginTop: '16px', padding: '8px 18px', background: '#1976d2', color: '#fff', border: 'none', borderRadius: '8px', cursor: 'pointer', fontWeight: '500', fontSize: '13px' }}
            >
              Reload Page
            </button>
          </div>
        </div>
      );
    }
    return this.props.children;
  }
}

ReactDOM.createRoot(document.getElementById('root')).render(
  <React.StrictMode>
    <GlobalErrorBoundary>
      <BrowserRouter>
        <App />
      </BrowserRouter>
    </GlobalErrorBoundary>
  </React.StrictMode>
);
