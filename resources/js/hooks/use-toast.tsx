"use client";

import * as React from "react";
import { Toast, ToastAction, ToastProps, ToastProvider, ToastViewport } from "@/components/ui/toast";

// Define Toast Context Type
interface ToastContextType {
  showToast: (toast: ToastProps) => void;
}

// Create Context
const ToastContext = React.createContext<ToastContextType | undefined>(
  undefined
);

export const useToast = () => {
  const context = React.useContext(ToastContext);
  if (!context) {
    throw new Error("useToast must be used within a ToastProvider");
  }
  return context;
};

// Toast Provider
export const ToastProviderWrapper = ({ children }: { children: React.ReactNode }) => {
  const [toasts, setToasts] = React.useState<ToastProps[]>([]);

  const showToast = (toast: ToastProps) => {
    setToasts((prevToasts) => [...prevToasts, toast]);
    setTimeout(() => {
      setToasts((prevToasts) => prevToasts.filter((t) => t !== toast));
    }, toast.duration || 3000); // Auto-dismiss after duration
  };

  return (
    <ToastContext.Provider value={{ showToast }}>
      <ToastProvider>
        {children}
        <ToastViewport>
          {toasts.map((toast, index) => (
            <Toast key={index} {...toast}>
              <div className="grid gap-1">
                {toast.title && <span className="font-bold">{toast.title}</span>}
                {toast.description && <span>{toast.description}</span>}
              </div>
              {toast.action && <ToastAction altText="Close">{toast.action}</ToastAction>}
            </Toast>
          ))}
        </ToastViewport>
      </ToastProvider>
    </ToastContext.Provider>
  );
};
