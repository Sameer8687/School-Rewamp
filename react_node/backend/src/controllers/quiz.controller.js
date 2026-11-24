/**
 * Quiz Controller — Handles Quiz listings, submissions, and review
 */
import QuizModel from '../models/Quiz.model.js';

export const getQuizzes = async (req, res, next) => {
  try {
    const quizzes = await QuizModel.getQuizzes(req.query);
    return res.json({ success: true, data: quizzes });
  } catch (error) {
    next(error);
  }
};

export const getSubmissions = async (req, res, next) => {
  try {
    const submissions = await QuizModel.getSubmissions(req.query);
    return res.json({ success: true, data: submissions });
  } catch (error) {
    next(error);
  }
};

export const getQuizReview = async (req, res, next) => {
  try {
    const { quizId, studentId } = req.params;
    if (!quizId || !studentId) {
      return res.status(400).json({ success: false, message: 'Quiz ID and Student ID are required' });
    }

    const review = await QuizModel.getQuizReview(quizId, studentId);
    return res.json({ success: true, data: review });
  } catch (error) {
    next(error);
  }
};

